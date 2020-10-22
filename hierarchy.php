<?php

/*
 * Hierarchy Library, simulating a tree with nodes.
 */


class Node
{
    function __construct($id, $parent, $value)
    {
        $this->id = $id;
        $this->parent = $parent;
        $this->value = $value;
    }

    //Returns a node value by its id
    function getValue()
    {
        return $this->value;
    }
}


class Tree
{
    public $data = array();

//A one-time initialization of $data
    /*
     * @throws NoDataReceivedException if incoming data is null.
     * @throws DataAlreadySetException if data has been set previously.
     * @throws WrongFormatException if data comes in in a format other than array.
      * */
    function setData($incomingData)
    {

        if ($incomingData === null) {
            throw new NoDataReceivedException('No data received.');
        }

        //Ensuring data has not been initialized already
        if (!empty($this->data)) {
            throw new DataAlreadySetException('Data has already been initialized.');
        }

        //Check if data is an array
        if (!is_array($incomingData)) {
            throw new WrongFormatException('Incoming data is not an array.');
        }

        //Loading the data as an array of Nodes
        foreach ($incomingData as $node) {
            try { //Incoming arrays may not be of correct format
                $nextNode = new Node($node['id'], $node['parent'], $node['value']);
            } catch (Exception $ex) {
                throw new WrongFormatException('Found nodes not set in correct format: id, parent, value.');
            }

            array_push($this->data, $nextNode);
        }
        echo 'Data has been set.';
    }

//Find root of data
    /*
         * @throws ObjectNotFound if no root was found.
          * */
    function getRoot()
    {

        $result = null;

        //Looping through all nodes to find root (which has a null parent)
        foreach ($this->data as $node) {
            if ((($node->parent) === null) && (!$result)) {
                $result = $node;
            }
        }
        if ($result === null) {
            throw new ObjectNotFoundException('Root');
        }
        return $result;
    }

    //Get the parent node by a child's id
    /*
     * @throws ParentNotFoundException if the parent node was not found.
      * */
    function getParent($childId)
    {
        $result = null;
        $childnode = $this->getNode($childId);
        $parentNode = null;
        try {
            $parentNode = $this->getNode($childnode->parent);
        } catch (ObjectNotFoundException $ex) {
            throw new ParentNotFoundException();
        }
        return $parentNode;
    }

    //A helper function to get complete Node by id
    /*
     * @throws NodeNotFoundException if incoming data is null.
    * */
    function getNode($id)
    {
        $result = null;

        foreach ($this->data as $node) {
            if (($node->id === $id) && (!$result)) {
                $result = $node;
            }
        }
        if ($result === null) {
            throw new NodeNotFoundException('Node with id: ' . $id . ' not found.');
        }

        return $result;
    }

    //Returns all children of a parent by parent's id
    function getChildren($parentId)
    {
        $children = array();

        $this->getNode($parentId); //Ensuring parent exists. Exception will be thrown from getNode() function if not.

        foreach ($this->data as $node) {
            if ($node->parent === $parentId) {
                array_push($children, $node);
            }
        }

        //An empty array indicates parent found, but has no children.
        return $children;
    }

    //Returns a node value by its id
    function getNodeValue($nodeId)
    {
        $node = $this->getNode($nodeId);

        return $node->getValue();
    }

}


//Various custom exceptions

class NoValueException extends Exception
{
    public function errorMessage()
    {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
            . ': <b>' . $this->getMessage();
        return $errorMsg;
    }
}

class NoDataReceivedException extends Exception
{
    public function errorMessage()
    {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
            . ': <b>' . $this->getMessage();
        return $errorMsg;
    }
}

class DataAlreadySetException extends Exception
{
    public function errorMessage()
    {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
            . ': <b>' . $this->getMessage();
        return $errorMsg;
    }
}

class WrongFormatException extends Exception
{
    public function errorMessage()
    {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
            . ': <b>' . $this->getMessage();
        return $errorMsg;
    }
}

class ObjectNotFoundException extends Exception
{
    public function errorMessage($whatNotFound)
    {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
            . ': <b>' . $this->getMessage() . '' . $whatNotFound . ' not found.';
        return $errorMsg;
    }
}

class ParentNotFoundException extends ObjectNotFoundException
{
    public function errorMessage($whatNotFound)
    {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
            . ': <b>' . $this->getMessage();
        return $errorMsg;
    }
}

class NodeNotFoundException extends ObjectNotFoundException
{
    public function errorMessage($whatNotFound)
    {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
            . ': <b>' . $this->getMessage();
        return $errorMsg;
    }
}