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
           Debug::log("Showing login form");
           
           view('auth.login');
       } catch (\Throwable $e) {
           // Log the exception
           Debug::log("Exception in showLoginForm: " . $e->getMessage());
           Debug::log("Stack trace: " . $e->getTraceAsString());
           
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
               Debug::log("Database connection error during login: " . $dbTest['message']);
               
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
           Debug::log("Login attempt for user: " . $request->nom_login);
       
           if (Auth::attempt($credentials)) {
               // Obtener el usuario autenticado
               $user = Auth::user();
               // Buscar el cliente asociado a este usuario
               $client = null;
               if ($user && isset($user->id)) {
                   $client = \App\Models\Client::findByUserId($user->id);
               }
               // Guardar el nombre real en la sesión si existe
               if ($client && $client->nom) {
                   session()->set('nom_real', $client->nom);
               } else if ($user) {
                   session()->set('nom_real', $user->username);
               }
               // Check if user is admin and redirect to admin dashboard
               if (Auth::isAdmin()) {
                   $adminDashboardUrl = BASE_URL . '/admin/';
                   Debug::log("Admin user logged in: " . $request->nom_login . ", redirecting to admin dashboard: " . $adminDashboardUrl);
                   redirect($adminDashboardUrl)->with('success', 'Benvingut al panell d\'administració')->send();
                   return;
               }

               // Get redirect URL if it exists (for non-admin users)
               $redirectTo = session()->getFlash('redirect_to');
               Debug::log('Valor de $redirectTo antes de normalizar: ' . var_export($redirectTo, true));
               if (!$redirectTo) {
                   $redirectTo = '/'; // Usar solo la barra para ir a la home
               }
               // Normalizar la URL para evitar duplicados
               if ($redirectTo === '/' || $redirectTo === '') {
                   $redirectTo = BASE_URL . '/';
               } else if (strpos($redirectTo, 'http') !== 0 && strpos($redirectTo, BASE_URL) !== 0) {
                   $redirectTo = BASE_URL . $redirectTo;
               }
               Debug::log('Valor de $redirectTo después de normalizar: ' . var_export($redirectTo, true));
               // Log successful login
               Debug::log("Login successful for user: " . $request->nom_login . ", redirecting to: " . $redirectTo);
               redirect($redirectTo)->with('success', 'Has iniciat sessió correctament')->send();
               return;
           }
           
           // Log failed login
           Debug::log("Login failed for user: " . $request->nom_login . " - Invalid credentials");

           back()->with('error', 'Credencials incorrectes')->withInput([
               'nom_login' => $request->nom_login
           ])->send();
           
       } catch (\Throwable $e) {
           // Log the exception
           Debug::log("Exception during login: " . $e->getMessage());
           Debug::log("Stack trace: " . $e->getTraceAsString());
           
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
           Debug::log("Showing register form");
           
           view('auth.register');
       } catch (\Throwable $e) {
           // Log the exception
           Debug::log("Exception in showRegisterForm: " . $e->getMessage());
           Debug::log("Stack trace: " . $e->getTraceAsString());
           
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
           $inTransaction = DB::inTransaction();
           if (!$inTransaction) {
               DB::beginTransaction();
           }
           
           try {
               // Create user
               $user = new User();
               $user->username = trim($request->nom_login);
               $user->email = trim($request->email);
               $user->password = password_hash($request->contrasena, PASSWORD_DEFAULT);
               $user->role = 'user';
               $user->created_at = date('Y-m-d H:i:s');
               $user->updated_at = date('Y-m-d H:i:s');
               $userSaved = $user->save();
               
               if (!$userSaved || !$user->id) {
                   throw new \Exception("Failed to save user");
               }
               
               // Create client
               $client = new Client();
               $client->user_id = $user->id;
               $client->nom = trim($request->nom);
               $client->cognom = trim($request->cognom ?? '');
               $client->email = trim($request->email);
               $client->tlf = trim($request->tlf ?? '');
               $client->nom_login = trim($request->nom_login);
               $client->contrasena = $request->contrasena; // Store plain password for legacy compatibility
               $client->rol = 0; // Regular user
               
               // Handle id_referit if it exists
               if (isset($request->id_referit)) {
                   $client->id_referit = (int)$request->id_referit;
               }
               
               $clientSaved = $client->save();
               
               if (!$clientSaved) {
                   throw new \Exception("Failed to save client");
               }
               
               // Commit transaction
               if (!$inTransaction) {
                   DB::commit();
               }
               
               // Log the user in
               Auth::login($user);
               
               redirect(BASE_URL)->with('success', "¡Benvingut, {$client->nom}!")->send();
           } catch (\Throwable $e) {
               // Rollback transaction if we started it
               if (!$inTransaction && DB::inTransaction()) {
                   DB::rollback();
               }
               throw $e;
           }
       } catch (\Throwable $e) {
           // Log the exception
           Debug::log("Exception during registration: " . $e->getMessage());
           Debug::log("Stack trace: " . $e->getTraceAsString());
           
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

   public static function adminRegisterUser(array $data): ?User
{
    \App\Core\Debug::log('Entrando en adminRegisterUser', $data);

    try {
        if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['role'])) {
            \App\Core\Debug::log('Faltan datos obligatorios');
            return null;
        }

        if (User::findByUsername($data['username']) || User::findByEmail($data['email'])) {
            \App\Core\Debug::log('Usuario o email duplicado');
            return null;
        }

        $user = new User();
        $user->username = trim($data['username']);
        $user->email = trim($data['email']);
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->role = $data['role'];
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        if (!$user->save()) {
            \App\Core\Debug::log('Error al guardar usuario');
            return null;
        }

        if ($user->role === 'user') {
            $client = new \App\Models\Client();
            $client->user_id = $user->id;
            $client->nom = $data['username'];
            $client->email = $data['email'];
            $client->nom_login = $data['username'];
            $client->contrasena = $data['password'];
            $client->rol = 0;
            $client->save();
            \App\Core\Debug::log('Cliente creado');
        }

        \App\Core\Debug::log('Usuario creado correctamente');
        return $user;
    } catch (\Throwable $e) {
        \App\Core\Debug::log("Error en adminRegisterUser: " . $e->getMessage());
        return null;
    }
}

}

