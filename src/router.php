<?php
class Router {
  private $routes;

  function __construct() {
    $this->routes = array(
      "index" => "index",
      "signup" => "signUp",
      "login" => "logIn",
      "login-user" => "loginUser",
      "home" => "home",
      "logout" => "logOut",
      "update-profile" => "updateProfile",
      "update-info" => "updateInfo",
      "upload-profile-picture" => "uploadProfilePicture",
      "change-password" => "changePassword",
      "delete-profile" => "deleteUser",
      "profile" => "profile",
      "create" => "create",
      "new-post" => "newPost",
      "posts" => "posts",
      "post" => "post",
      "edit" => "edit",
      "edit-post" => "editPost",
      "delete-post" => "deletePost",
      "comment" => "comment",
      "search" => "search"
    );
  }

  public function lookup($query) {
    if(array_key_exists($query, $this->routes)) {
      return $this->routes[$query];
    } else {
      return false;
    }
  }
}