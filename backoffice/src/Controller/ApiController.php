<?php

/**
 * Api controller, two urls are handled, the first to get a liste of espece beginning with the a string, the second to find all echouage in a given time frame
 * @author Mathieu Roux & Emma Finck
 * @version 1.0.0
 */

namespace App\Controller;

use App\Entity\Echouage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Espece;
use App\Entity\Zone;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/espece/{nom_espece}", name="nom_espece")
     * Fetch the species that have a name that starts with nom_espece
     * Used by both the backoffice and the frontoffice
     */
    public function espece(string $nom_espece): Response
    {
        $em = $this->getDoctrine()->getManager();

        // Safe from sql injection
        $especes = $em->getRepository(Espece::class)
            ->findEntitiesByName($nom_espece);

        $especes_options = array();
        foreach($especes as $id => $espece_entity) {
            array_push($especes_options, $espece_entity->getEspece());
        }

        $response = new Response(json_encode($especes_options));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set("Access-Control-Allow-Origin", "*");

        return $response;
    }

    /**
     * @Route("/espece/{debut}/{fin}/{nom_espece}", name="echouage_find")
     * Fetch all echouages of a specific species during a specific time frame
     * Used exclusively by the frontoffice
     */
    public function echouage_find(int $debut, int $fin, string $nom_espece): Response
    {
        $em = $this->getDoctrine()->getManager();

        $zones = $em->getRepository(Zone::class)->findAll();

        // Safe from sql injection
        $espece = $em->getRepository(Espece::class)
            ->findEntityByName($nom_espece);

        if($espece == null) {
            // In the frontend, espece is a dropdown menu, shouldn't be invalid
            $response = new Response(json_encode(array('Invalid')));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set("Access-Control-Allow-Origin", "*");

            return $response;
        } else {
            $tableau = array();

            foreach($zones as $_id => $zone_entity) {
                $liste = array();
                
                $echouages = $em->getRepository(Echouage::class)
                    ->findDuring($zone_entity->getId(), $espece->getId(), $debut, $fin);

                $data = array();
                // Add up all the echouages with the same date
                foreach($echouages as $_id2 => $echouage_entity) {
                    if(array_key_exists($echouage_entity->getDate(), $data)) {
                        $data[$echouage_entity->getDate()] += $echouage_entity->getNombre();
                    } else {
                        $data[$echouage_entity->getDate()] = $echouage_entity->getNombre();
                    }
                }
                $tableau[$zone_entity->getZone()] = $data;
            }

            $response = new Response(json_encode($tableau));
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set("Access-Control-Allow-Origin", "*");

            return $response;
        }
    }
}
