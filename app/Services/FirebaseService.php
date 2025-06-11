<?php

namespace App\Services;

use Kreait\Firebase\Auth;
use Kreait\Firebase\Firestore;
use Kreait\Firebase\Storage;
use Kreait\Firebase\Database;
use Exception;

class FirebaseService
{
    protected $auth;
    protected $firestore;
    protected $storage;
    protected $database;

    public function __construct()
    {
        $this->auth = app('firebase.auth');
        $this->firestore = app('firebase.firestore');
        $this->storage = app('firebase.storage');
        $this->database = app('firebase.database');
    }

    public function verifyIdToken($idToken)
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            return $verifiedIdToken;
        } catch (Exception $e) {
            throw new Exception('Unauthorized - Invalid Token');
        }
    }

    public function saveToFirestore($collection, $document, $data)
    {
        try {
            $docRef = $this->firestore->database()->collection($collection)->document($document);
            $docRef->set($data);
            return true;
        } catch (Exception $e) {
            throw new Exception('Failed to save to Firestore: ' . $e->getMessage());
        }
    }

    public function updateFirestoreDocument($collection, $document, $data)
    {
        try {
            $docRef = $this->firestore->database()->collection($collection)->document($document);
            $docRef->update($data);
            return true;
        } catch (Exception $e) {
            throw new Exception('Failed to update Firestore document: ' . $e->getMessage());
        }
    }

    public function getFromFirestore($collection, $document)
    {
        try {
            $docRef = $this->firestore->database()->collection($collection)->document($document);
            $snapshot = $docRef->snapshot();
            
            if ($snapshot->exists()) {
                return $snapshot->data();
            }
            
            return null;
        } catch (Exception $e) {
            throw new Exception('Failed to get data from Firestore: ' . $e->getMessage());
        }
    }
}