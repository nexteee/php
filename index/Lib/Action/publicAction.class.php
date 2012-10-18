<?php
class publicAction extends baseAction{
    function verify(){
        import("ORG.Util.Image");
        Image::buildImageVerify();
    }
}