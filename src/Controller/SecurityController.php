<?php

namespace App\Controller;

use App\Events;
use App\Entity\User;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
   /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $helper): Response
    {
       // dump($helper->getLastAuthenticationError());
        return $this->render('Security/login.html.twig', [
            // dernier username saisi (si il y en a un)
            'last_username' => $helper->getLastUsername(),
            // La derniere erreur de connexion (si il y en a une)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

     /**
     * La route pour se deconnecter.
     * 
     * Mais celle ci ne doit jamais être executé car symfony l'interceptera avant.
     *
     *
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/check_email", name="check_email")
     */
    public function CheckEmail()
    {
        return $this->render('Security/reset_password.html.twig');
    }

    /**
     * @Route("/find_email", name="find_email")
     */
    public function FindEmail(Request $request,EventDispatcherInterface $eventDispatcher)
    {
        $user = new User();
        $email = $request->request->get("inputEmail", "valeur par défaut si le champ n'existe pas");

        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->findOneBy(['email' => $email]);
        ////////////////////////////////////
        if(!$user){
            $var=true;
            $this->addFlash("notice", "This is an error message");  
            $token = sha1(uniqid($user, true));
        }else{
            $user_=$user;
            $var=false;
            $this->addFlash("notice", "This is a success message");
            $token = sha1(uniqid('$user_', true));
            //On déclenche l'event
            //$eventDispatcher = new EventDispatcher();
            //$event = new GenericEvent($user);
            //$eventDispatcher->dispatch(Events::USER_REGISTERED, $event);
                }
       

        return $this->render('Security/reset_password.html.twig',[
            'form'=> $email,
            'var'=>$var,
            'tok'=>$token,
            //'date'=>$date,

            ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
{
    $form = $this->createForm(ContactType::class);
    

    return $this->render('contact/contact.html.twig', [
                    'our_form' => $form,
                    'our_form' => $form->createView(),
                ]);
            }



#=======================((   ))===========================#
#====================(           )========================#
#=================( search function )=====================#
#====================(           )========================#
#=======================((   ))===========================#
   
    /**
     * @Route("/search_off", name="search_off")
     */
     public function searchAction2(Request $request){
        $user = new User();

         $data = $request->request->get('search');
         //$data=null;

         $em = $this->getDoctrine()->getManager();
         /* $query = $em->createQuery(
             'SELECT p.email,p.username,p.roles,p.fullname FROM App\Entity\User p
             WHERE p.username LIKE :data')
             ->setParameter('data',$data); */
         //========================================================================
        $query = $em->createQuery('select p.email,p.username,p.roles,p.fullname from App\Entity\User p WHERE p.fullname LIKE :data')
        //==========================================================================
        //==========================================================================
        //$query = $em->createQuery('select p.email,p.username,p.roles,p.fullname from App\Entity\User p')
        //->setParameter('data',$data )
        ->setParameter('data','%'.$data.'%');
             $res = $query->getResult();
             //dump($res);die;
             
             return $this->render('search.html.twig', array(
                 'res' => $res,
                 'val' => $data
                ));
             }


#=======================((   ))===========================#
#====================(           )========================#
#=================( search function2 )====================#
#====================(           )========================#
#=======================((   ))===========================#
   
    // /**
    //  * @Route("/search", name="search")
    //  */
    //  public function searchAction(Request $request){
    //     $searchTerm = $request->request->get('search');      
    //         //$results = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $searchTerm]);

    //         $em = $this->getDoctrine()->getManager();
    //          $results = $em->getRepository(User::class)->findOneBy(['username' => $searchTerm]);
    //          dump($results);

    //          return $this->render('search.html.twig', [
    //             'res' => $results,
    //             'val' => $searchTerm

    // ]);
  
    //  $response = new JsonResponse();
    //  $response->setData(array('classifiedList' => $results));
    //  return $response;
    //          }


#=======================((   ))===========================#
#====================(           )========================#
#=================( search function3 )====================#
#====================(           )========================#
#=======================((   ))===========================#

//   /** 
//    * @Route("/search2", name="search2")
//    */ 
// public function ajaxAction(Request $request) { 
//     $searchTerm = $request->request->get('search');     
//     $students = $this->getDoctrine() 
//        ->getRepository(User::class) 
//        ->findAll();  
       
//     if ($request->isXmlHttpRequest()) {  
//        $jsonData = array();  
//        $idx = 0;  
//        foreach($students as $student) {  
//           $temp = array(
//              'username' => $student->getUsername(),  
//              'email' => $student->getEmail(),  
//           );   
//           $jsonData[$idx++] = $temp;  
//        } 
//        return new JsonResponse($jsonData); 
//     } else { 
//        return $this->render('search2.html.twig'); 
//     } 
//  }    
///////////////////////////////////////////////////////////////////////
///**
//  * @Route("search/search/{search}", name="search")
//  * @param  Request               $request Request instance
//  * @param  string                $search  Search term
//  * @return Response|JsonResponse          Response instance
//  */
// public function searchAction(Request $request, string $search= null)
// {
//     if (!$request->isXmlHttpRequest()) {
//         return $this->render("search/search.html.twig");
//     }

//     if (!$searchTerm = trim($request->Request->get("search", $search))) {
//         return new JsonResponse(["error" => "Search term not specified."], Response::HTTP_BAD_REQUEST);
//     }

//     $em = $this->getDoctrine()->getManager();
//     if (!($results = $em->getRepository(User::class)->findOneBy(['username' => $searchTerm]))) {
//         return new JsonResponse(["error" => "No results found."], Response::HTTP_NOT_FOUND);
//     }
// debug($results);die;
//     return new JsonResponse([
//         "html" => $this->renderView("search/search.ajax.twig", ["results" => $results]),
//     ]);
// }



////////////////////////////////////////////////////////////////////////////////////////////////////
     /** 
      * @Route("/recherche", name="recherche")
      */ 
      public function ListeAction(Request $request,string $search=null)
      {
 
         //$search ='jane_admin';
         
                
        //$search = $request->request->get('inputs');
          $em = $this->getDoctrine()->getEntityManager();
     //if($request->request->get('some_var_name')){                  
         if ($request->isXmlHttpRequest()) {
         //$search = $request->request->get('inputs');        
         
              $repo = $em->getRepository(User::class);
              //$datas = $repo->findBy(['username' => 'jane_admin']);
              $datas = $repo->findAll();           

              foreach($datas as $data ){
                $output[]=array($data->getEmail(),$data->getFullname(),$data->getUsername());
                //$output=['email' => $data->getEmail() , 'fullname' => $data->getFullname(), 'username' => $data->getUsername()]; 
                //$output[]=['email' => $data->getEmail() , 'Fullname' => $data->getFullname(), 'username' => $data->getUsername()];
              }              

              return new JsonResponse($output);             
              }        
              return $this->render('search/recherche.html.twig');
      } 
     
}
