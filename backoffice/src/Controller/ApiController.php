<?php

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
     */
    public function espece(string $nom_espece): Response
    {
        $em = $this->getDoctrine()->getManager();
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
     */
    public function echouage_find(int $debut, int $fin, string $nom_espece): Response
    {
        $em = $this->getDoctrine()->getManager();

        $zones = $em->getRepository(Zone::class)->findAll();

        $espece = $em->getRepository(Espece::class)
            ->findEntityByName($nom_espece);

        if($espece == null) {
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
