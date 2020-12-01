<?php


namespace helper\Internet\Request;



use interfaces\Internet\Request\FileInterface;

class File implements FileInterface
{
    protected $name = '';
    protected $type = '';
    protected $tmp_name = '';
    protected $size = '';

    public function __construct($name = '',$type = '',$tmp_name = '',$error = '',$size = '')
    {
        $this->name = $name;
        $this->type = $type;
        $this->tmp_name = $tmp_name;
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
     public function getSize(): string
     {
         return $this->size;
     }

    public function getError()
    {
        // TODO: Implement getError() method.
    }
}
