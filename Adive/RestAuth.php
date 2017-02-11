<?php
namespace Adive;

class RestAuth extends \Adive\Kernel
{
    /**
     * @var resource
     */
    public $activeAuth = array(
        'rest.id' => 'your_prefered_id',
        'rest.start' => 'start_date',
        'rest.end' => 'end_date',
        'rest.salt' => 'my_salt',
        'rest.key' => 'Empty'
    );
    
    /**
     * @var resource
     */
    protected $hash;
    
    /**
     * @var resource
     */
    protected $privateKey;
    
    /**
     * @var resource
     */
    protected $publicKey;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->publicKey = base64_encode(json_encode($this->activeAuth));
        $this->generatePrivate();
        $_SESSION['rest.vars'] = $this->activeAuth;
    }

    /**
     * AUTOLOAD of Procedures
     */
    public function generatePrivate()
    {
        $this->privateKey = md5($this->publicKey);
        $_SESSION['rest.pk'] = $this->privateKey;
        $_SESSION['rest.pu'] = $this->publicKey;
    }
    
    /**
     * LOAD Configuration
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }
    
    /**
     * LOAD WEB of Procedures
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }
    
    public function getHash()
    {
        return sha1($this->hash);
    }
    
    public function call()
    {
        $this->next->call();
    }
}
