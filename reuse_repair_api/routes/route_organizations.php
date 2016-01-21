<?php

/* ===========================================================================
LIST ALL
=========================================================================== */

$app->get('/organizations', function () use ($app, $db) {
    $results = array();
    foreach ($db->organizations() as $row) {

        $org_type = null;

        $query = $db->itemtype->where('id', intval($row['org_type']));
        if($data = $query->fetch()) {
            $org_type = $data['description'];
        }

        $results[] = array(
            'id'        => $row['id'],
            'name'      => $row['name'],
            'service'   => $org_type,
            'street_1'  => $row['street1'],
            'street_2'  => $row['street2'],
            'city'      => $row['city'],
            'state'     => $row['state'],
            'zip_code'  => $row['zip'],
            'webpage'   => $row['webpage'],
            'phone'     => $row['phone'],
            'notes'     => $row['notes'],
        );
    }

    $app->response()->header("Content-Type", "application/json");
    echo json_encode(["data" => $results]);
});


/* ===========================================================================
LIST ONE
=========================================================================== */

$app->get('/organizations/:id', function ($id) use ($app, $db) {

    $app->response()->header("Content-Type", "application/json");

    $query = $db->organizations()->where('id', $id);
    if ($row = $query->fetch()) {

        //get the type as text instead of id
        $org_type = null;
        $type_query = $db->itemtype->where('id', intval($row['org_type']));
        if($data = $type_query->fetch()) {
            $org_type = $data['description'];
        }

        $results = array(
            'id'        => $row['id'],
            'name'      => $row['name'],
            'service'   => $org_type,
            'street_1'  => $row['street1'],
            'street_2'  => $row['street2'],
            'city'      => $row['city'],
            'state'     => $row['state'],
            'zip_code'  => $row['zip'],
            'webpage'   => $row['webpage'],
            'phone'     => $row['phone'],
            'notes'     => $row['notes'],
        );

        echo json_encode(["data" => $results]);

    } else {

        $app->response()->setStatus(404);
        echo json_encode([
            'status'  => 404,
            'message' => "Organizations ID=$id was not found in server",
        ]);
    }
});

/* ===========================================================================
INSERT ONE
=========================================================================== */
$app->post('/organization', function () use ($app, $db) {
    $app->response()->header("Content-Type", "application/json");
    
    //check if data already exist
    $name = $app->request()->post('name');
    $street1 = $app->request()->post('street1');
    $zip = $app->request()->post('zip');

    $query = $db->organizations()->where('name=? AND street1=? AND zip=?', $name, $street1, $zip);
    if ($data = $query->fetch()) {
        $app->response()->setStatus(409);
        $id = $data['id'];
        echo json_encode(array(
            'status'  => 409,
            'message' => "Organization with same name and physical address already exist in DB. See /organization/$id",
        ));
    } else {

        //no duplicate found
        $post_data = $app->request()->post();
        $result = $db->organizations->insert($post_data); //returns the index where it was inserted

        $query = $db->organizations()->where('id', $result);
        if ($row = $query->fetch()) {
            //get the type as text instead of id
            $org_type = null;
            $type_query = $db->itemtype->where('id', intval($row['org_type']));
            if($data = $type_query->fetch()) {
                $org_type = $data['description'];
            }

            $results = array(
                'id'        => $row['id'],
                'name'      => $row['name'],
                'service'   => $org_type,
                'street_1'  => $row['street1'],
                'street_2'  => $row['street2'],
                'city'      => $row['city'],
                'state'     => $row['state'],
                'zip_code'  => $row['zip'],
                'webpage'   => $row['webpage'],
                'phone'     => $row['phone'],
                'notes'     => $row['notes'],
            );

            echo json_encode(["message" => "Successfull insertion to DB.", "data" => $results]);

        } else {

            $app->response()->setStatus(500);
            echo json_encode([
                'status'  => 500,
                'message' => "Error. Could not find organization recently added. ",
            ]);
        }
    }

    
});


/* ===========================================================================
UPDATE ONE
=========================================================================== */
$app->put('/organization/:id', function($id) use($app, $db){

    $app->response()->header("Content-Type", "application/json");

    $query = $db->organizations()->where("id", intval($id));

    if ($row = $query->fetch()) {

        $post_data = $app->request()->put();
        $result = $query->update($post_data); //result = true/false

        //get the type as text instead of id
        $row = $db->organizations()->where("id", intval($id))->fetch();
        $org_type = null;
        $type_query = $db->itemtype->where('id', intval($row['org_type']));
        if($data = $type_query->fetch()) {
            $org_type = $data['description'];
        }

        $results = array(
            'id'        => $row['id'],
            'name'      => $row['name'],
            'service'   => $org_type,
            'street_1'  => $row['street1'],
            'street_2'  => $row['street2'],
            'city'      => $row['city'],
            'state'     => $row['state'],
            'zip_code'  => $row['zip'],
            'webpage'   => $row['webpage'],
            'phone'     => $row['phone'],
            'notes'     => $row['notes'],
        );

        echo json_encode(["message" => "Successfull insertion to DB.", "data" => $results]);

    } else {

        $app->response()->setStatus(404);
        echo json_encode([
            'status'  => 404,
            'message' => "Organizations ID=$id was not found in server",
        ]);
    }
});

/* ===========================================================================
DELETE ONE
=========================================================================== */

$app->delete('/organization/:id', function($id) use($app, $db){
    $app->response()->header("Content-Type", "application/json");

    $query = $db->organizations()->where('id', $id);
    if($query->fetch()){
        $result = $query->delete();
        echo json_encode(array(
            "status" => true,
            "message" => "Organization deleted successfully"
        ));
    } else {
        $app->response()->setStatus(404);
        echo json_encode(array(
            "status" => 404,
            "message" => "Organization id $id does not exist"
        ));
    }
});


