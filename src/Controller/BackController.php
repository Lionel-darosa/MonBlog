<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/11/2017
 * Time: 14:39
 */

namespace Controller;

class BackController
{
    /**
     *
     */
    public function Posts()
    {
        echo "Posts ";
    }

    /**
     * @param $id
     */
    public function Post($id)
    {
        echo "Post ". $id;
    }

    /**
     *
     */
    public function NewPost()
    {
        echo "NewPost ";
    }

    /**
     * @param $id
     */
    public function UpdatePost($id)
    {
        echo "UpdatePost ". $id;
    }

    /**
     * @param $id
     */
    public function DeletePost($id)
    {
        echo "DeletePost ".$id;
    }

    /**
     * @param $id
     */
    public function DeleteComment($id)
    {
        echo "DeleteComment ".$id;
    }

}