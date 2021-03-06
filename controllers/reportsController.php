<?php
class reportsController extends controller {

    public function __construct() {
        parent::__construct();

        $u = new Users();

        if($u->isLogged() == false){
        	header("Location: ".BASE."/login");
        }
    }

    public function index() {
    	$data = array();

        $u = new Users();
        $u->setLoggedUser();
        $company = new Companies($u->getCompany());
        $data['company_name'] = $company->getName();
        $data['user_email'] = $u->getEmail();
        $data['user_name'] = $u->getName();

        
        
        if($u->hasPermission('reports_view')){
        	
            
            
            $this->loadTemplate("reports", $data);

        }else{
            header("Location: ".BASE);
        }	
    }

    public function sales() {
        $data = array();

        $u = new Users();
        $u->setLoggedUser();
        $company = new Companies($u->getCompany());
        $data['company_name'] = $company->getName();
        $data['user_email'] = $u->getEmail();
        $data['user_name'] = $u->getName();

        $data['statuses'] = array(
            '0'=>'Aguardando Pgto.',
            '1'=>'Pago',
            '2'=>'Cancelado'
        );
        
        if($u->hasPermission('reports_view')){
            
            
            
            $this->loadTemplate("reports_sales", $data);

        }else{
            header("Location: ".BASE);
        }   
    }

    public function sales_pdf(){
        $data = array();

        $u = new Users();
        $u->setLoggedUser();
        $company = new Companies($u->getCompany());
        $data['company_name'] = $company->getName();
        $data['user_email'] = $u->getEmail();
        $data['user_name'] = $u->getName();

        $data['statuses'] = array(
            '0'=>'Aguardando Pgto.',
            '1'=>'Pago',
            '2'=>'Cancelado'
        );
        
        if($u->hasPermission('reports_view')){
            $client_name = addslashes($_GET['client_name']);
            $period1 = addslashes($_GET['period1']);
            $period2 = addslashes($_GET['period2']);
            $status = addslashes($_GET['status']);
            $order = addslashes($_GET['order']);

            $s = new Sales();

            $data['sales_list'] = $s->getSalesFistered($u->getCompany(), $client_name, $period1, $period2, $status, $order );   
            $data['filters'] = $_GET ; 

            $this->loadLibrary('mpdf60/mpdf');    
            ob_start();
            $this->loadView("reports_sales_pdf", $data);
            $html = ob_get_contents();
            ob_end_clean();

            $mpdf = new mPDF();
            $mpdf->WriteHTML($html);
            $mpdf->Output();
            header("Location: ".BASE);
        }       
    }
}    