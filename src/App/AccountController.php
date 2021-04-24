<?php
namespace App;

use App\AccountModel;
class AccountController {

    private $repository;

    public function __construct()
    {
        $this->repository = new AccountModel;
    }

    public function createUser()
    {
       $name = $_POST['name'];
       $money = $_POST['money'];

       $this->repository->createUser($name, $money);

       header('location: ../index.php');
    }

    public function resetUsers()
    {
        $this->repository->reset();

        header('location: ../index.php');
    }

    public function getNews()
    {
        echo json_encode( $this->repository->getNews());

    }

    public function find()
    {
        $name = $_GET['name'];
        header('Content-Type: application/json');
        
        echo json_encode($this->repository->find($name));
    }

}