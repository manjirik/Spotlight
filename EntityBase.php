<?php

  namespace Spotlight;
  use JMS\Serializer\Annotation as Jms;

  /**
   * Class EntityBase
   *
   * This is the most basic Entity class
   * ALL spotlight-sdk Entities MUST extend this class
   *
   * @package Spotlight
   */
    abstract class EntityBase implements EntityInterface
    {

      /**
       * every entity MUST have an id
       *
       * It doesnt really matter, how the id is constructed, but there must be two conditions fullfilled:
       *
       * * the id MUST be unique within the given context (per package)
       *
       * * the id MUST carry all information needed to create the entity from it.
       *
       * What does this mean?
       *
       * e.g. for Entity Brand, it's enough to store the brand id.
       *
       * e.g. for a product, there is more information needed.
       *
       * as a rule of thumb: The ID must encode all parameters needed to __construct the entity
       *
       * @Jms\Type("string")
       *
       * @var string
       */
      protected $id;

      /**
       * every entity SHOULD have a displayname
       *
       * @Jms\Type("string")
       *
       * @var string
       */

      protected $displayname;

      /**
       * every entity SHOULD have a machine readable name
       *
       * @Jms\Type("string")
       *
       * @var string
       */
      protected $name;

      /**
       * {@inheritdoc}
       */
      public function id() {
        return $this->id;
      }

      /**
       * {@inheritdoc}
       */
      public function displayName()
      {
        return $this->displayname;
      }

      /**
       * {@inheritdoc}
       */
      public function name() {
        return $this->name;
      }

    }