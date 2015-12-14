<?php namespace App;

class OrderAdapter {

    protected $phone;
    protected $storeid;

    /**
     * OrderAdapter constructor.
     * @param $storeid - your favourite store ID
     * @param $phone - your phone number used for orders
     */
    function __construct($storeid, $phone)
    {
        $this->storeid = $storeid;
        $this->phone = $phone;
    }

    /**
     * Get status of a specific order
     * @param $orderid - id of order
     * @return String - status of the order
     */
    public function getStatusOf($orderid)
    {
        $orders = $this->fetchAll();
        $index = array_search($orderid, array_column($orders, 0));

        return $orders[$index][1];
    }

    /**
     * Fetch all orders of your phone number
     * @return array - multi dimensional array with [orderid, status]
     */
    public function fetchAll()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://order.dominos.ca/orderstorage/GetTrackerData?StoreID='
            . $this->storeid . '&Phone=' . $this->phone);

        $response = $response->getBody();

        preg_match_all('/<OrderID>[0-9]*-[0-9]*-[0-9]*#(.*)<\/OrderID>/', $response, $orderid);
        preg_match_all('/<OrderStatus>(.*)<\/OrderStatus>/', $response, $status);

        $result = array();

        for ($i = 0; $i < count($orderid[1]); $i ++)
        {
            $result[$i] = array($orderid[1][$i], $status[1][$i]);
        }

        return $result;
    }
}