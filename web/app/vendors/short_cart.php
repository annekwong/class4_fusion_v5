<?php

class ShoppingCart
{
    private $listing = array();
    
    public function check($product)
    {
        $len = count($this->listing);
        for ($i = 0; $i < $len; $i++)
        {
            if ($this->listing[$i]['number'] == $product['number'])
                return $i;
        }
        return -1;
    }
    
    public function add($product)
    {
        $check = $this->check($product);
        if ($check == -1)
        {
            array_push($this->listing, $product);
            return true;
        }
        else
            return false;
    }
    
    public function delete($product)
    {
        $i = $this->check($product);
        if ($i != -1)
            unset($this->listing[$i]);
    }
    
    public function getAll()
    {
        return $this->listing;
    }
    
}
