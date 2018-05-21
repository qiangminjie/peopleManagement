<?php
/***************************************************************************************************
 * File Description:
 *
 * Created by   jieqiangmin            jieqiangmin@microwu.com           14:10/2018/2/11
 * CopyRight    北京小悟科技有限公司      http://www.microwu.com
 * Method List:
 *  0 =
 *  1 =
 *  2 =
 * Update History:
 * Author       Time            Contennt
 ***************************************************************************************************/



class Menu_test{
    private $text ;
    private $icon ;
    private $nodes ;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param mixed $nodes
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
    }



}


