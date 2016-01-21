<?php

/* ===========================================================================
LIST ALL
=========================================================================== */

$app->get('/items', function () use ($app, $db) {
    $app->response()->header("Content-Type", "application/json");

    $result = array();

    foreach ($db->items() as $row) {

        $item_type     = null;
        $item_category = null;

        //get the item type
        $query = $db->itemtype->where('id', intval($row['type']));
        if ($data = $query->fetch()) {
            $item_type = ['type_id' => $data['id'], 'type_desc' => $data['description']];
            //$item_type = $data['description'];
        }

        //get the category the item is under
        $query = $db->itemcategories->where('id', intval($row['category']));
        if ($data = $query->fetch()) {
            $item_category = ['cat_id' => $data['id'], 'cat_desc' => $data['description']];
            //$item_category = $data['description'];
        }

        $result[] = array(
            'id'         => $row['id'],
            'name'       => $row['name'],
            'type'       => $item_type,
            'category'   => $item_category,
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        );
    }

    echo json_encode(["data" => $result]);
});

/* ===========================================================================
LIST ONE
=========================================================================== */
$app->get('/item/:id', function ($id) use ($app, $db) {
    $app->response()->header("Content-Type", "application/json");

    $query = $db->items()->where('id', intval($id));
    if ($row = $query->fetch()) {

        $item_type     = null;
        $item_category = null;

        //get the item type
        $query = $db->itemtype->where('id', intval($row['type']));
        if ($data = $query->fetch()) {
            $item_type = ['type_id' => $data['id'], 'type_desc' => $data['description']];
            //$item_type = $data['description'];
        }

        //get the category the item is under
        $query = $db->itemcategories->where('id', intval($row['category']));
        if ($data = $query->fetch()) {
            $item_category = ['cat_id' => $data['id'], 'cat_desc' => $data['description']];
            //$item_category = $data['description'];
        }

        $result[] = array(
            'id'         => $row['id'],
            'name'       => $row['name'],
            'type'       => $item_type,
            'category'   => $item_category,
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        );

        echo json_encode(["data" => $result]);

    } else {

        $app->response()->setStatus(404);
        echo json_encode(array(
            'status'  => 404,
            'message' => "Items ID=$id was not found in server",
        ));
    }
});

/* ===========================================================================
INSERT ONE
=========================================================================== */
$app->post('/item', function () use ($app, $db) {
    $app->response()->header("Content-Type", "application/json");

    //check if data already exist
    $name = $app->request()->post('name');

    $query = $db->items()->where('name', $name);
    if ($data = $query->fetch()) {
        $app->response()->setStatus(409);
        $id = $data['id'];
        echo json_encode(array(
            'status'  => 409,
            'message' => "Item with same name already exist in DB. See /item/$id",
        ));
    } else {

        //no duplicate found
        $post_data = $app->request()->post();
        $result    = $db->items->insert($post_data); //returns the index

        $query = $db->items()->where('id', $result);
        if ($row = $query->fetch()) {

            $item_type     = null;
            $item_category = null;

            //get the item type
            $query = $db->itemtype->where('id', intval($row['type']));
            if ($data = $query->fetch()) {
                $item_type = ['type_id' => $data['id'], 'type_desc' => $data['description']];
                //$item_type = $data['description'];
            }

            //get the category the item is under
            $query = $db->itemcategories->where('id', intval($row['category']));
            if ($data = $query->fetch()) {
                $item_category = ['cat_id' => $data['id'], 'cat_desc' => $data['description']];
                //$item_category = $data['description'];
            }

            $results = array(
                'id'         => $row['id'],
                'name'       => $row['name'],
                'type'       => $item_type,
                'category'   => $item_category,
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            );

            echo json_encode(["data" => $results]);

        } else {

            $app->response()->setStatus(500);
            echo json_encode([
                'status'  => 500,
                'message' => "Error. Could not find item category recently added. ",
            ]);
        }
    }

});

/* ===========================================================================
UPDATE ONE
=========================================================================== */
$app->put('/item/:id', function ($id) use ($app, $db) {

    $app->response()->header("Content-Type", "application/json");

    $query = $db->items()->where("id", intval($id));

    if ($row = $query->fetch()) {

        $post_data = $app->request()->put();
        $result    = $query->update($post_data); //returns true/false
        $row       = $db->items()->where("id", intval($id))->fetch();

        $item_type     = null;
        $item_category = null;

        //get the item type
        $query = $db->itemtype->where('id', intval($row['type']));
        if ($data = $query->fetch()) {
            $item_type = ['type_id' => $data['id'], 'type_desc' => $data['description']];
            //$item_type = $data['description'];
        }

        //get the category the item is under
        $query = $db->itemcategories->where('id', intval($row['category']));
        if ($data = $query->fetch()) {
            $item_category = ['cat_id' => $data['id'], 'cat_desc' => $data['description']];
            //$item_category = $data['description'];
        }

        $results = array(
            'id'         => $row['id'],
            'name'       => $row['name'],
            'type'       => $item_type,
            'category'   => $item_category,
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        );

        echo json_encode(["message" => "Successfull insertion to DB.", "data" => $results]);

    } else {

        $app->response()->setStatus(404);
        echo json_encode([
            'status'  => 404,
            'message' => "Item ID=$id was not found in server",
        ]);
    }
});


/* ===========================================================================
DELETE ONE
=========================================================================== */

$app->delete('/item/:id', function ($id) use ($app, $db) {
    $app->response()->header("Content-Type", "application/json");

    $query = $db->items()->where('id', $id);
    if ($query->fetch()) {
        $result = $query->delete();
        echo json_encode(array(
            "status"  => true,
            "message" => "Item deleted successfully",
        ));
    } else {
        $app->response()->setStatus(404);
        echo json_encode(array(
            "status"  => 404,
            "message" => "Item id $id does not exist",
        ));
    }
});