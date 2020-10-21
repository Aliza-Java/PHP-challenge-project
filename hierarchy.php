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
        if ($this->value === null) {
            throw new Exception('No value found.');
        }
        return $this->value;
    }
}


class Tree
{
    public $data = array();

//A one-time initialization of $data
    function setData($incomingData)
    {

        if ($incomingData === null) {
            throw new Exception('No data received.');
        }

        //Ensuring data has not been initialized already
        if (!empty($this->data)) {
            throw new Exception('Data has already been initialized.');
        }

        //Check if data is an array
        if (!is_array($incomingData)) {
            throw new Exception('Incoming data is not an array.');
        }

        //Loading the data as an array of Nodes
        foreach ($incomingData as $node) {
            try { //Incoming arrays may not be of correct format
                $nextNode = new Node($node['id'], $node['parent'], $node['value']);
            } catch (Exception $ex) {
                throw new Exception('Found nodes not set in correct format: id, parent, value.');
            }

            array_push($this->data, $nextNode);
        }
        echo 'Data has been set.';
    }

//Find root of data
    function getRoot()
    {

        $result = null;

        //Looping through all nodes to find root (which has a null parent)
        foreach ($this->data as $node) {
            if ((($node->parent) === null) && (!$result)) {
                $result = $node;
            }
        }
        return $result;
    }

    //Get the parent node by a child's id
    function getParent($childId)
    {
        $result = null;

        //Looping through all nodes to find parent with matching id
        foreach ($this->data as $node) {
            if ((($node->id) === $childId) && (!$result)) {
                //Getting actual node by id of parent
                $result = $this->getNode($node->parent);
            }
        }
        return $result;
    }

    //A helper function to get complete Node by id
    function getNode($id)
    {
        $result = null;

        foreach ($this->data as $node) {
            if (($node->id === $id) && (!$result)) {
                $result = $node;
            }
        }
        return $result;
    }

    //Returns all children of a parent by parent's id
    function getChildren($parentId)
    {
        $children = array();

        if ($this->getNode($parentId) === null) {
            throw new Exception('Id not found.');
        }

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

        if ($node === null) { //Node was not found
            throw new Exception('Id not found.');
        }

        return $node->getValue();
    }

}