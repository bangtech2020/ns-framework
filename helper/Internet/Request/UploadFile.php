<?php


namespace helper\Internet\Request;


use interfaces\Request\UploadFileInterface;

class UploadFile implements UploadFileInterface
{
    protected $name = '';
    protected $type = '';
    protected $tmp_name = '';
    protected $error = '';
    protected $size = '';

    public function __construct($name = '', $type = '', $tmp_name = '', $error = '', $size = '')
    {
        $this->name = $name;
        $this->type = $type;
        $this->tmp_name = $tmp_name;
        $this->error = $error;
        $this->size = $size;
    }

     /**
      * @return string
      */
     public function getName(): string
     {
         return $this->name;
     }

     /**
      * @return string
      */
     public function getType(): string
     {
         return $this->type;
     }

     /**
      * @return string
      */
     public function getTmpName(): string
     {
         return $this->tmp_name;
     }

     /**
      * @return string
      */
     public function getError(): string
     {
         return $this->error;
     }

     /**
      * @return string
      */
     public function getSize(): string
     {
         return $this->size;
     }
 }
