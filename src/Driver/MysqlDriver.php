<?php
declare(strict_types=1);

namespace CuePhp\Orm;

use PDO;
use PDOExcption;

final class MysqlDriver
{

    public $pdo;

    private $__dbName = 'mysql';

    private $__dbHost = 'mysql';

    private $__dbUser = 'default';

    private $__dbPwd = 'secret';

    private $__dbPort = '3306';

    function __construct( $dbName , $dbHost , $dbUser , $dbPwd , $dbPort = '3306' )
    {
        $this->__dbName = $dbName;
        $this->__dbHost = $dbHost;
        $this->__dbUser = $dbUser;
        $this->__dbPwd = $dbPwd;
        $this->__dbPort = $dbPort;
        $this->connect();
    }

    protected function connect()
    {
        if( !$this->pdo )
        {
            $dsn = 'mysql:dbname=' . $this->__dbName  . ';host=' . $this->__dbHost . ';port=' . $this->__dbPort . ';charset=utf8';
            $user =  $this->__dbUser;
            $pwd = $this->__dbPwd;
            try{
                $this->pdo = new PDO($dsn , $user , $pwd , [ PDO::ATTR_PERSISTENT => true ]);
                return true;
            } catch( PDOExcption $e ){
                die( $e->getMessage() );
                return false;
            }
        }
        else{
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        }
    }

    private function __preQuery( $query )
    {
        return $this->pdo->prepare($query);
    }

    public function execute( $query , $values = [] )
    {
        $values = is_array( $values ) ? $values : array( $values );
        $statement = $this->__preQuery( $query );
        $statement->execute( $values );
        return $statement;
    }

    public function fetch( $query ,  $values= [] )
    {
        return $this->execute( $query , $values )->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll( $query , $values=[]  )
    {
        return $this->execute( $query , $values )->fetchAll( PDO::FETCH_ASSOC );
    }
    
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}