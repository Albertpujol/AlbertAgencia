<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ReservasController extends ControllerBase{
    
    protected $model;
    protected $view;
    private $conf;
    
    /** 
     * Funcio construct que utilitzem per indicarli la configuració que s'aplicarà
     * Quin serà el model associat a aquest controlador el qual processara les dades
     * i quina serà la vista que mostrara la informació
     */
    
    public function __construct($arr) {
    parent::__construct($arr);
    //carregar la configuració
    $this->conf=$this->config;
    $this->model= new ReservasModel($arr);
    $this->view=new View();
    
    
    
    }
    
    /**
     * afegir configuració per ruta publica, enllaços, css ,js...
     */
    public function index(){
               
        $this->view->setProp($this->model->getDataout());
        //afegir configuració per ruta publica, enllaços, css ,js...
        $this->view->addProp(array('APP_W'=>$this->conf->APP_W));
        $this->view->setTemplate(APP.'/public/tpl/register.html');
        $this->view->render();
        
        
    }
    

    
     /** 
      * funció que utilitzem per comprobar si un usuari te alguna reserva pendent
      * en el cas de que no en tingui podra reservar
      * Un cop comprobat introduim registre a reserves i serveis reservats
      * 
      * @param string $usuari: s'utilitza per a guardar el email del usuari el qual esta loguejat
      * @param string $places: s'utilitza per a guardar el numero de persones que voldran el servei que es passa desde formulari
      * @param string $id_servei:s'utilitza per guardar el id del servei a reservar
      * @param string $user: es retorna en aquest variable el id dle usuari que vol fer la reserva
      * @param string $html:es retorna el resultat del bucle per recorrer el array associatiu
      * @param string $id_usuari: guardem el valor del $html
      * @param string $reservas: retorna si el usuari pot fer reserva o no
      * @param string $fer_reserva:retorna el insert que ha fet a la base de dades
      * @param string $serveis_reservats: retorna el insert a la taula serveis_reservats
     */
    
    public function comprobar_reservas(){
        //session:set('email',$email);
       if(isset($_SESSION["usuari"]))
       {
        $html="";
        $usuari = $_SESSION["usuari"];
        $places = $_POST['opcio'];
        print_r($places);
        $id_servei = $_POST['id'];//id del servei que volem reservar
        $user = $this->model->seleccionar_iduser($usuari);//ens retorna el id del usuari que vol fer la reserva
        foreach ($user as $campo){//obtenim el id del usuari
        $html = $html.$campo['id'];   
        }
        $id_usuari = $html;//ho posem en una variable, la qual reconeguem
        print_r($id_usuari);
        $reservas = $this->model->seleccionar_reservas($id_usuari);//Ens retorna true o false depenent si el usuari te reserves pendents de pagar, o mai a fet cap
        }
        if($reservas == true)
        {
            $fer_reserva = $this->model->fer_reserva($id_usuari,$id_servei);
            $serveis_reservats = $this->model->serveis_reservats($id_servei,$id_usuari,$places);
            print_r("Reserva realitzada correctament!!!");
        }else{
            print_r("Te encara una reserva pendent de abaonar!");
        }
        
        
       }
       
    
            
    
}
    
    
