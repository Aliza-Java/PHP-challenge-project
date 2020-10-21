<?php
require_once 'hierarchy.php';

use PHPUnit\Framework\TestCase;

//Unit tests for hierarchy.php
class nodeTest extends TestCase
{

    public $sampleArray;


    protected function setUp(): void
    {
        global $sampleArray;
        $sampleArray = [
            ['id' => 2, 'parent' => 1, 'value' => 'child1'],
            ['id' => 1, 'parent' => null, 'value' => 'root'],
            ['id' => 3, 'parent' => 1, 'value' => 'child2'],
            ['id' => 4, 'parent' => 2, 'value' => 'grandchild']
        ];

    }

    protected function tearDown(): void
    {
    }

    //Node->Constructor test
    function testConstructor()
    {
        $newNode = new Node(1, 2, 'value');
        $this->assertEquals($newNode->id, 1);
        $this->assertEquals($newNode->parent, 2);
        $this->assertEquals($newNode->value, 'value');
    }


    //Node->getValue() tests
    function testGetValueNoValueFoundThrowsException()
    {
        $myNode = new Node(1, 2, null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No value found.');
        $myNode->getValue();
    }


    function testGetValueReturnsValue()
    {
        $myNode = new Node(1, 2, 'my value');

        $result = $myNode->getValue();
        $this->assertEquals('my value', $result);
    }


    //Tree->setData() tests
    function testIncomingNullThrowsException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No data received.');

        $myTree = new Tree();
        $myTree->setData(null);
    }

    function testSettingDataTwiceThrowsException()
    {
        global $sampleArray;
        $anotherArray = [[4, 5, 'value2'], [6, 7, 'value3']];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Data has already been initialized.');

        $myTree = new Tree();

        $myTree->setData($sampleArray);
        $myTree->setData($anotherArray);
    }

    function testIncomingDataNotArrayThrowsException()
    {

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Incoming data is not an array.');

        $myTree = new Tree();

        $myTree->setData(5);
    }

    function testIncomingDataWrongFormatThrowsException()
    {
        $badArray = [
            ['id' => 2, 'parent' => 1, 'value' => 'child1'],
            ['id' => 1, 'parent' => null, 'value' => 'root'],
            ['id' => 3, 'parent' => 1],
            ['id' => 4, 'parent' => 2, 'value' => 'grandchild']
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Found nodes not set in correct format: id, parent, value.');

        $myTree = new Tree();

        $myTree->setData($badArray);
    }

    function testSetDataSetsDataCorrectly()
    {
        global $sampleArray; //From this file, preparing array to test

        $node1 = new Node(2, 1, 'child1');
        $node2 = new Node(1, null, 'root');
        $node3 = new Node(3, 1, 'child2');
        $node4 = new Node(4, 2, 'grandchild');

        $myTree = new Tree();

        $myTree->setData($sampleArray);
        $this->assertEquals(array($node1, $node2, $node3, $node4), $myTree->data);
    }

    function testCorrectOutputString()
    {
        global $sampleArray;

        $this->expectOutputString('Data has been set.');

        $myTree = new Tree();
        $myTree->setData($sampleArray);
    }


    //Tree->getRoot() tests
    //If a node doesn't actually have a root, returned result should be null
    function testGetRootWithNoRootReturnsNull()
    {
        $rootlessArray = [['id' => 2, 'parent' => 1, 'value' => 'child1'],
            ['id' => 5, 'parent' => 4, 'value' => 'greatgrandchild'],
            ['id' => 3, 'parent' => 1, 'value' => 'child2'],
            ['id' => 4, 'parent' => 2, 'value' => 'grandchild']];

        $myTree = new Tree();
        $myTree->setData($rootlessArray);

        $root = $myTree->getRoot();
        $this->assertNull($root);
    }

    function testGetRootReturnsRoot()
    {
        global $sampleArray;
        $expectedRoot = new Node(1, null, 'root');

        $myTree = new Tree();
        $myTree->setData($sampleArray);

        $root = $myTree->getRoot();
        $this->assertEquals($expectedRoot, $root);
    }


    //Tree->getParent() tests

    function testGetParentChildNotFoundReturnsNull()
    {
        global $sampleArray;

        $myTree = new Tree();
        $myTree->setData($sampleArray);
        $parent = $myTree->getParent(6);
        $this->assertNull($parent);
    }

    function testGetParentReturnsChild()
    {
        global $sampleArray;
        $expectedParent = new Node(2, 1, 'child1');

        $myTree = new Tree();
        $myTree->setData($sampleArray);

        $parent = $myTree->getParent(4);
        $this->assertEquals($expectedParent, $parent);
    }

    //Tree->getNode() tests
    function testGetNodeIdNotFoundReturnsNull()
    {
        global $sampleArray;

        $myTree = new Tree();
        $myTree->setData($sampleArray);

        $result = $myTree->getNode(5);
        $this->assertNull($result);
    }

    function testGetNodeReturnsNode()
    {
        global $sampleArray;
        $expectedNode = new Node(3, 1, 'child2');

        $myTree = new Tree();
        $myTree->setData($sampleArray);

        $result = $myTree->getNode(3);
        $this->assertEquals($expectedNode, $result);
    }


    //Tree->getChildren() tests

    //Testing if existing yet childless node has children
    function testGetChildrenNoChildrenReturnsEmpty()
    {
        global $sampleArray;

        $myTree = new Tree();
        $myTree->setData($sampleArray);

        $result = $myTree->getChildren(4);
        $this->assertEmpty($result);
    }

    //Testing if non-existing node returns exception
    function testGetChildrenByNonExistingParentThrowsException()
    {
        global $sampleArray;

        $myTree = new Tree();
        $myTree->setData($sampleArray);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Id not found.');
        $myTree->getChildren(5);
    }

    //Testing if existing and has children returns children
    function testGetChildrenReturnsChildren()
    {
        global $sampleArray;
        $expectedChild1 = new Node(2, 1, 'child1');
        $expectedChild2 = new Node(3, 1, 'child2');
        $expectedChildren = array($expectedChild1, $expectedChild2);

        $myTree = new Tree();
        $myTree->setData($sampleArray);

        $result = $myTree->getChildren(1);
        $this->assertEquals($expectedChildren, $result);
    }


    //Tree->getNodeValue() tests

    function testGetNodeValueNonExistingIdThrowsException()
    {
        global $sampleArray;

        $myTree = new Tree();
        $myTree->setData($sampleArray);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Id not found');
        $myTree->getNodeValue(5);
    }

    function testGetNodeValueReturnsValue()
    {
        global $sampleArray;

        $myTree = new Tree();
        $myTree->setData($sampleArray);

        $result = $myTree->getNodeValue(4);
        $this->assertEquals('grandchild', $result);
    }

}