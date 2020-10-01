<?php
require_once 'node.php';

use PHPUnit\Framework\TestCase;

//Unit tests for node.php
class nodeTest extends TestCase
{

    public $sampleArray;

    protected function setUp(): void
    {
        //This array will be used in multiple tests
        global $sampleArray;
        $sampleArray = [
            ['id' => 2, 'parent' => 1, 'value' => 'child1'],
            ['id' => 1, 'parent' => null, 'value' => 'root'],
            ['id' => 3, 'parent' => 1, 'value' => 'child2'],
            ['id' => 4, 'parent' => 2, 'value' => 'grandchild']
        ];

        global $data;
        //Cleaning up so that data starts anew in every function (and is not re-set)
        $data = array();
    }

    protected function tearDown(): void
    {
    }

    //Constructor test
    function testConstructor()
    {
        $newNode = new Node(1, 2, 'myValue');
        $this->assertEquals($newNode->id, 1);
        $this->assertEquals($newNode->parent, 2);
        $this->assertEquals($newNode->value, 'myValue');
    }

    //setData() tests

    function testIncomingNullThrowsException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No data received.');

        setData(null);
    }

    function testSettingDataTwiceThrowsException()
    {
        global $sampleArray;
        $anotherArray = [[4, 5, 'value2'], [6, 7, 'value3']];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Data has already been initialized.');

        setData($sampleArray);
        setData($anotherArray);
    }

    function testIncomingDataNotArrayThrowsException()
    {

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Incoming data is not an array.');

        setData(5);
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
        setData($badArray);
    }

    function testSetDataSetsDataCorrectly()
    {
        global $data; //From node.php
        global $sampleArray; //From this file, preparing array to test

        $node1 = new Node(2, 1, 'child1');
        $node2 = new Node(1, null, 'root');
        $node3 = new Node(3, 1, 'child2');
        $node4 = new Node(4, 2, 'grandchild');

        setData($sampleArray);
        $this->assertEquals(array($node1, $node2, $node3, $node4), $data);
    }

    function testCorrectOutputString()
    {
        global $sampleArray;

        $this->expectOutputString('Data has been set.');
        setData($sampleArray);
    }


    //getRoot() tests

    //If a node doesn't actually have a root, returned result should be null
    function testGetRootWithNoRootReturnsNull()
    {
        $rootlessArray = [['id' => 2, 'parent' => 1, 'value' => 'child1'],
            ['id' => 5, 'parent' => 4, 'value' => 'greatgrandchild'],
            ['id' => 3, 'parent' => 1, 'value' => 'child2'],
            ['id' => 4, 'parent' => 2, 'value' => 'grandchild']];
        setData($rootlessArray);
        $root = getRoot();
        $this->assertNull($root);
    }

    function testGetRootReturnsRoot()
    {
        global $sampleArray;
        $expectedRoot = new Node(1, null, 'root');
        setData($sampleArray);
        $root = getRoot();
        $this->assertEquals($expectedRoot, $root);
    }


    //getParent() tests

    function testGetParentChildNotFoundReturnsNull()
    {
        global $sampleArray;

        setData($sampleArray);
        $parent = getParent(6);
        $this->assertNull($parent);
    }

    function testGetParentReturnsChild()
    {
        global $sampleArray;
        $expectedParent = new Node(2, 1, 'child1');

        setData($sampleArray);
        $parent = getParent(4);
        $this->assertEquals($expectedParent, $parent);
    }

    //getNode() tests

    function testGetNodeIdNotFoundReturnsNull()
    {
        global $sampleArray;

        setData($sampleArray);
        $result = getNode(5);
        $this->assertNull($result);
    }

    function testGetNodeReturnsNode()
    {
        global $sampleArray;
        $expectedNode = new Node(3, 1, 'child2');

        setData($sampleArray);
        $result = getNode(3);
        $this->assertEquals($expectedNode, $result);
    }


    //getChildren() tests

    //Testing if existing yet childless node has children
    function testGetChildrenNoChildrenReturnsEmpty()
    {
        global $sampleArray;

        setData($sampleArray);
        $result = getChildren(4);
        $this->assertEmpty($result);
    }

    //Testing if non-existing node returns exception
    function testGetChildrenByNonExistingParentThrowsException()
    {
        global $sampleArray;

        setData($sampleArray);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Id not found.');
        getChildren(5);
    }

    //Testing if existing and has children returns children
    function testGetChildrenReturnsChildren()
    {
        global $sampleArray;
        $expectedChild1 = new Node(2, 1, 'child1');
        $expectedChild2 = new Node(3, 1, 'child2');
        $expectedChildren = array($expectedChild1, $expectedChild2);

        setData($sampleArray);
        $result = getChildren(1);
        $this->assertEquals($expectedChildren, $result);
    }


    //getNodeValue() tests

    function testGetNodeValueNonExistingIdThrowsException()
    {
        global $sampleArray;

        setData($sampleArray);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Id not found');
        getNodeValue(5);
    }

    function testGetNodeValueReturnsValue()
    {
        global $sampleArray;

        setData($sampleArray);
        $result = getNodeValue(4);
        $this->assertEquals('grandchild', $result);
    }

}