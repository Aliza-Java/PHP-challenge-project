<?php

/*
 * Node Library
 */


class Node
{
    function __construct($id, $parent, $value)
    {
        $this->id = $id;
        $this->parent = $parent;
        $this->value = $value;
    }
}

$data = array();

//A one-time initialization of $data
function setData($incomingData)
{
    global $data;

    if ($incomingData == null) {
        throw new Exception('No data received.');
    }

    //Ensuring data has not been initialized already
    if (!empty($data)) {
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

        array_push($data, $nextNode);
    }
    echo 'Data has been set.';
}

//Find root of data
function getRoot()
{

    $result = null;
    global $data;

    //Looping through all nodes to find root (which has a null parent)
    foreach ($data as $node) {
        if (($node->parent) == null) {
            $result = $node;
        }
    }

    return $result;
}

//Get the parent node by a child's id
function getParent($childId)
{
    $result = null;
    global $data;

    //Looping through all nodes to find parent with matching id
    foreach ($data as $node) {
        if (($node->id) == $childId) {
            //Getting actual node by id of parent
            $result = getNode($node->parent);
        }
    }

    return $result;
}

//A helper function to get complete Node by id
function getNode($id)
{
    $result = null;
    global $data;

    foreach ($data as $node) {
        if ($node->id == $id) {
            $result = $node;
        }
    }
    return $result;
}

//Returns all children of a parent by parent's id
function getChildren($parentId)
{
    global $data;
    $children = array();

    if (getNode($parentId) == null) {
        throw new Exception('Id not found.');
    }

    foreach ($data as $node) {
        if ($node->parent == $parentId) {
            array_push($children, $node);
        }
    }

    //An empty array indicates parent found, but has no children.
    return $children;
}

//Returns a node value by its id
function getNodeValue($nodeId)
{
    global $data;

    $node = getNode($nodeId);

    if ($node == null) { //Node was not found
        throw new Exception('Id not found.');
    }

    return $node->value;
}

