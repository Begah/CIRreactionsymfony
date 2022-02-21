<?php

/**
 * Homepage controller, only handles the base url
 * @author Mathieu Roux & Emma Finck
 * @version 1.0.0
 */

namespace App\Controller;

use Symfony\Component\Form;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

use App\Form\AccueilType;

use App\Entity\Echouage;
use App\Entity\Zone;
use App\Entity\Espece;

class AccueilController extends AbstractController
{
    /**
     * Entry point of the homepage
     * @Route("", name="accueil")
     */
    public function acceuil(Request $request): Response
    {
        // Custom form : need to fetch available zones manually to send to the form
        $zones = $this->getDoctrine()->getManager()
            ->getRepository(Zone::class)
            ->findAll();
        // Formating of the zones list, adding a default 'all' option 
        $zones_options = array();
        $zones_options['All'] = -1;
        foreach ($zones as $id => $zone_entity) {
            $zones_options[$zone_entity->getZone()] = $id;
        }

        $form = $this->createForm(AccueilType::class, null, [
            'zones' => $zones_options
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Form is submitted => fetch the entered zone/especes to generate the table
            $zone = $form->get('Zone')->getData();
            $espece = $form->get('Espece')->getData();
            // Hidden value, used to figure out if it's the first time the form was submitted or not, used exclusively for css animation
            $alreadysubmitted = intval($form->get('Submitted')->getData());

            $tab = $this->tableau($zone, $espece);

            // tab == null corresponds to a invalid espece name
            if ($tab == null) {
                return $this->render('accueil/index.html.twig', [
                    'form' => $form->createView(),
                    'display_table' => true,
                    'tableau' => array(),
                    'anim_state' => $alreadysubmitted < 2 ? 2 : 3
                ]);
            } else {
                return $this->render('accueil/index.html.twig', [
                    'form' => $form->createView(),
                    'display_table' => true,
                    'tableau' => $tab,
                    'anim_state' => $alreadysubmitted < 2 ? 2 : 3
                ]);
            }
        }

        return $this->render('accueil/index.html.twig', [
            'form' => $form->createView(),
            'display_table' => false,
            'anim_state' => 1
        ]);
    }

    /**
     * Generate the data of the homepage table given a zone id and a espece name
     */
    public function tableau(int $zone, string $espece): ?array
    {
        // Fetch the corresponding espece id to the espece name, safe from sql injections
        $espece_entity = $this->getDoctrine()->getManager()
            ->getRepository(Espece::class)
            ->findEntityByName($espece);

        if ($espece_entity != null) {
            // Valid entity name
            $liste_zones = null;

            if ($zone == -1) { // Fetch all zones
                $liste_zones = $this->getDoctrine()->getManager()
                    ->getRepository(Zone::class)
                    ->findAll();
            } else { // Fetch a single zone
                $liste_zones = array($this->getDoctrine()->getManager()
                    ->getRepository(Zone::class)
                    ->find($zone));
            }

            // A dictionnary with years as keys and a list of echouages per zones as value
            $liste = array();
            // The table header, ["Annee", "zone1", "zone2", ...]
            $header = array("Annee");

            // Current zones index in header, starts at 1 because 0 is "Annee"
            $zone_column_index = 1;
            $column_count = 1 + count($liste_zones);

            // Not all zones have information for each year, possible that a zone started reporting echouages later or earlier, or skipped a year,
            // instead of assuming the value is 0, the value is considered nan, !in database, no entry has getNombre() at 0, so unclear!
            $year_per_zones = array_fill(0, $column_count - 1, 0);

            // Used for the next for loop, instead of using a default value like 1e9
            $max_echouage = 0;

            foreach ($liste_zones as $id => $zone_entity) {
                array_push($header, $zone_entity->getZone());
                // For each zone, add the zone's name to the header and then find all echouages that are in that zone

                $echouage = $this->getDoctrine()->getManager()
                    ->getRepository(Echouage::class)
                    ->findAny($zone_entity->getId(), $espece_entity->getID());

                // For each echouage, add it to the liste
                foreach ($echouage as $echouage_entity) {
                    if (array_key_exists($echouage_entity->getDate(), $liste) == false) {
                        // First echouage of the zone with that date
                        $liste[$echouage_entity->getDate()] = array_fill(0, $column_count, 0);
                        $liste[$echouage_entity->getDate()][0] = $echouage_entity->getDate();
                        $year_per_zones[$zone_column_index - 1]++;
                    } else {
                        // First echouage of the zone with that date, but another echouage of another zone had that date before
                        if ($liste[$echouage_entity->getDate()][$zone_column_index] == 0) {
                            $year_per_zones[$zone_column_index - 1]++;
                        }
                    }

                    // There exists multiple entries for the same species, date and zone, so assuming we are supposed to add them all up, could be done with sql
                    $liste[$echouage_entity->getDate()][$zone_column_index] += $echouage_entity->getNombre();
                    $max_echouage = max($max_echouage, $echouage_entity->getNombre());
                }
                $zone_column_index++;
            }

            // Calculate and format all the data
            $tableau = array();
            $annees = array_keys($liste);
            sort($annees);

            $average = array_fill(0, $column_count - 1, 0);
            $min = array_fill(0, $column_count - 1, $max_echouage);
            $max = array_fill(0, $column_count - 1, 0);

            foreach ($annees as $index => $annee) {
                array_push($tableau, $liste[$annee]);

                for ($zone_id = 0; $zone_id < $column_count - 1; $zone_id++) {
                    $average[$zone_id] += $liste[$annee][$zone_id + 1];
                    $min[$zone_id] = min($min[$zone_id], $liste[$annee][$zone_id + 1]);
                    $max[$zone_id] = max($max[$zone_id], $liste[$annee][$zone_id + 1]);
                }
            }
            for ($i = 0; $i < $column_count - 1; ++$i) {
                // Prevent division by 0!
                $average[$i] = $year_per_zones[$i] == 0 ? 0 : round($average[$i] / $year_per_zones[$i]);
                $max[$i] = $year_per_zones[$i] == 0 ? 0 : $max[$i];
                $min[$i] = $year_per_zones[$i] == 0 ? 0 : $min[$i];
            }

            return array(
                'header' => $header,
                'list' => $tableau,
                'averages' => $average,
                'mins' => $min,
                'maxs' => $max
            );
        }

        //Error, espece does not exist
        return null;
    }
}
