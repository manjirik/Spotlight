<?php 

 namespace Spotlight;

 /**
  * Interface EntityInterface
  *
  * This is the most basic Interface
  * ALL spotlight-sdk Entities MUST implement this
  *
  * @package Spotlight
  */
 interface EntityInterface {

  /**
   * returns an unique identifier for the Entity
   *
   * can be different depending on the entity type
   * e.g. the Brand ID for Brand
   * or the SKU-like product identifier,
   * BUT the id MUST be unique between all entities of a given type
   *
   * @return string
   */
  public function id();

  /**
   * returns an human readable name for the Entity
   *
   * The displayName should always be something, that can be used on screen.
   * What should be displayed (Name of a Product, etc)?
   *
   * @return string
   */
  public function displayName();

  /**
   * returns an machine readable name for the Entity
   *
   * the name should always be the machine readable counterpart of the displayname
   * it might be used for markup (id={name}, anchors, as well as creation of consistent URL-fragments /something/{name}/more
   *
   * @return string
   */
  public function name();

 }