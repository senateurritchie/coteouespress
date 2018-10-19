<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Director;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DirectorFixtures extends Fixture{

    public function load(ObjectManager $manager){

       /* $data = ["Tonyiyke Ezendigbo","Chris Oyenso","Emma Anyaka","Emmanuel Anyak","Ajay Naidu","GREG ODUTAYO","Virginie Brac et Myriam Cottias","Derrick Shaw","Alexandre Moors","RENÉ SAMPAIO","KÁTIA LUND","Marcello Thedford","Alfred Robbins","Claudio Del Punta","STEPHEN VISSER","Dennis Rowe","JOSHUA COATES","Jamison Brandi","Mary Wells","Juan Andrés Arango Garcia","Scott D. Goldstein","STEVEN AYROMLOOI","Sheldon Candis","Rachel Perkins","Ty Hodges","Qasim 'Q' Basir","Michael Samuels","Tony Sebastian Ukpo","Marcus Dreeke","Mykel Shannon Jenkins","Grayson Stroud","Spike Lee","Bazi Gete","Cora Anne","Rubin Whitmore II","Bonginhlahla Ncube","Ernest 'Tron' Anderson","Carl Rousseau Gilliard","Joshua Sinclair","LaShirl Smith","Jean-Claude La Marre","Jamal Hill","Patrick Pierre","DOMINIQUE CABRERA","Paddy Houlihan","Edford Banuel Jr","Parrish Smith","Trina Montreuil Brown","Lamarcus Tinker","DIDIER BIVEL","Paul Apel Papel","Henry Okoro","Chika Anadu","Joy Dickson","Chibuike Ibe","Wonder O. Obazi","Bayo Alawiye","Neville Osai","Kunle Afolayan","Jeta Amata","Ifeanyi Ikpoenyi","UDUAK-OBONG Patrick","Charles Uwagbai","Desmond Ovbiagele","Onyekachi Ejim","Gabriel Moses","Alex Mouth","Nnoshiri Charles Brain","Stephanie Okereke","Aquila Njamah","Mercy Johnson","Ruth Kadiri","SISANDA NOMUSA QWABE","Alex Gansallo","Rambuda Ronald","Osayande Agbontaen","OSANYANDE AGBONTAEN","OLUSEGUN ALFRED ADEBAYO","Mike Bamiloye","Joseph Yemi","Mattew Chijioke","Omoniyi Adeoye","Joseph Yemi Adepoju","Ndubuisi Oko","Kingsley Ogoro","Yemi Adepoju","Kennedy Kihire","COLETTE NWADIKE","Oheneba Kwame Acheampong","Tanwie Elvis","Akin' Harrison","Ekwa Msangi-Omar","Tope Oshin","George Kura","Alain Guikou","Maxwell Akwesi Amuni","Willie Geker","Benjamin Odiwuor","Nnamdi Odunze","Victor Okpala","Kofi Asamoah","Adze Ugah","kalumbu Pikasa","Spielworks","Erica Anyadike","Simiyu Barasa","Alan Oyugi","Aggie Nyagari","sergio graciano","Steven Nyeko","Ruge Mutahaba","Mad","Muss","Mary Migui","LAURENE ABDALLAH","MULINDWA Richards","GRACE KAHAKIMUNTHALI","Benjamin 'Boy-Genius' ratita","Charmaine Zulu","Shirley Frimpong-Manso","Ken Attoh","Paul Igwe","Darel Rooot","Billy Bob NdiveLifongo","Lucy Chodota","Jordan Riber","Ben CHIADIKA","Corder productions","Carol Odongo","Antar Laniyan","Adeoye Bakare","Tubosun Olasimbo","Emeem ISONG","YOLANDE GNÉKPIÉ BOGUI","Balaji Dawodu","Patience Oghre","Biodun Williams","Paulo Brito","Ivan Quarshigah","Nation Media Group",
        ];

        foreach ($data as $key => $el) {
            $e = new Director();
            $e->setName($el);
            $manager->persist($e);
        }
        
        $manager->flush();*/
    }
}

