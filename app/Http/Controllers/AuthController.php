<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Auth;
use App\Core\DB;
use App\Core\Debug;
use App\Models\User;
use App\Models\Client;

class AuthController {

   /**
    * Show the login form
    */
   public function showLoginForm(): void
   {
       try {
           // Log the action
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Showing login form");
           }
           
           view('auth.login');
       } catch (\Throwable $e) {
           // Log the exception
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Exception in showLoginForm: " . $e->getMessage());
               Debug::log("Stack trace: " . $e->getTraceAsString());
           }
           
           // Display error
           http_response_code(500);
           echo "Error loading login form: " . $e->getMessage();
       }
   }
   
   /**
    * Process login attempt
    */
   public function login(Request $request)
   {
       try {
           // Test database connection first
           $dbTest = DB::testConnection();
           if (!$dbTest['success']) {
               // Log the database connection error
               if (class_exists('\\App\\Core\\Debug')) {
                   Debug::log("Database connection error during login: " . $dbTest['message']);
               }
               
               // Redirect with error message
               back()->with('error', 'Error de conexión a la base de datos: ' . $dbTest['message'])->withInput([
                   'nom_login' => $request->nom_login
               ])->send();
               return;
           }
           
           $credentials = [
               'username' => $request->nom_login,
               'password' => $request->contrasena,
           ];
           
           // Log login attempt
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Login attempt for user: " . $request->nom_login);
           }
       
           if (Auth::attempt($credentials)) {
               // Get redirect URL if it exists
               $redirectTo = session()->getFlash('redirect_to', '/productes/index.php');
               
               // Log successful login
               if (class_exists('\\App\\Core\\Debug')) {
                   Debug::log("Login successful for user: " . $request->nom_login . ", redirecting to: " . $redirectTo);
               }
               
               redirect($redirectTo)->with('success', 'Has iniciat sessió correctament')->send();
               return;
           }
           
           // Log failed login
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Login failed for user: " . $request->nom_login . " - Invalid credentials");
           }

           back()->with('error', 'Credencials incorrectes')->withInput([
               'nom_login' => $request->nom_login
           ])->send();
           
       } catch (\Throwable $e) {
           // Log the exception
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Exception during login: " . $e->getMessage());
               Debug::log("Stack trace: " . $e->getTraceAsString());
           }
           
           // Redirect with error message
           back()->with('error', 'Error durante el inicio de sesión: ' . $e->getMessage())->withInput([
               'nom_login' => $request->nom_login
           ])->send();
       }
   }

   /**
    * Show the registration form
    */
   public function showRegisterForm(): void
   {
       try {
           // Log the action
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Showing register form");
           }
           
           view('auth.register');
       } catch (\Throwable $e) {
           // Log the exception
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Exception in showRegisterForm: " . $e->getMessage());
               Debug::log("Stack trace: " . $e->getTraceAsString());
           }
           
           // Display error
           http_response_code(500);
           echo "Error loading registration form: " . $e->getMessage();
       }
   }

   /**
    * Process registration
    */
   public function register(Request $request)
   {
       try {
           // Test database connection first
           $dbTest = DB::testConnection();
           if (!$dbTest['success']) {
               back()->with('error', 'Error de conexión a la base de datos: ' . $dbTest['message'])->withInput([
                   'nom_login' => $request->nom_login,
                   'email' => $request->email,
                   'nom' => $request->nom,
                   'cognom' => $request->cognom,
                   'tlf' => $request->tlf
               ])->send();
               return;
           }
           
           // Start transaction
           DB::beginTransaction();
           
           try {
               // Create user
               $user = new User();
               $user->username = trim($request->nom_login);
               $user->email = trim($request->email);
               $user->password = password_hash($request->contrasena, PASSWORD_DEFAULT);
               $user->role = 'user';
               $user->created_at = date('Y-m-d H:i:s');
               $user->updated_at = date('Y-m-d H:i:s');
               $user->save();
               
               // Get the inserted user ID
               $userId = $user->id;
               
               if (!$userId) {
                   throw new \Exception("Failed to get user ID after insertion");
               }
               
               // Create client
               $client = new Client();
               $client->user_id = $userId;
               $client->nom = trim($request->nom);
               $client->cognom = trim($request->cognom ?? '');
               $client->email = trim($request->email);
               $client->tlf = trim($request->tlf ?? '');
               $client->nom_login = trim($request->nom_login);
               $client->contrasena = $request->contrasena; // Store plain password for legacy compatibility
               $client->rol = 0; // Regular user
               $client->save();
               
               // Commit transaction
               DB::commit();
               
               // Log the user in
               Auth::login($user);
               
               redirect('/productes/index.php')->with('success', "¡Benvingut, {$client->nom}!")->send();
           } catch (\Throwable $e) {
               // Rollback transaction
               DB::rollback();
               throw $e;
           }
       } catch (\Throwable $e) {
           // Log the exception
           if (class_exists('\\App\\Core\\Debug')) {
               Debug::log("Exception during registration: " . $e->getMessage());
               Debug::log("Stack trace: " . $e->getTraceAsString());
           }
           
           // Redirect with error message
           back()->with('error', 'Error durante el registro: ' . $e->getMessage())->withInput([
               'nom_login' => $request->nom_login,
               'email' => $request->email,
               'nom' => $request->nom,
               'cognom' => $request->cognom,
               'tlf' => $request->tlf
           ])->send();
       }
   }

   /**
    * Process logout
    */
   public function logout(){
       Auth::logout();
       redirect('/auth/show-login.php')->with('success', 'Has tancat la sessió correctament')->send();
   }
}
