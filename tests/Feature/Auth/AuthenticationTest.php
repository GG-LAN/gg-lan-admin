<?php

use App\Models\User;

// test('login screen can be rendered', function () {
//     $response = $this->get('/login');

//     $response->assertStatus(200);
// });

// test('users can authenticate using the login screen', function () {
//     $user = User::factory()->create([
//         'email'    => 'auth@test.com',
//         'password' => bcrypt('authpassword'),
//         'admin'    => 1
//     ]);

//     $response = $this->post('/login', [
//         'email' => $user->email,
//         'password' => 'authpassword',
//     ]);

//     $this->assertAuthenticated();
//     $response->assertRedirect(route('dashboard', absolute: false));
// });

// test('users can not authenticate with invalid password', function () {
//     $user = User::factory()->create([
//         'email'    => 'auth@test.com',
//         'password' => bcrypt('authpassword'),
//         'admin'    => 1
//     ]);

//     $this->post('/login', [
//         'email' => $user->email,
//         'password' => 'wrong-password',
//     ]);

//     $this->assertGuest();
// });

// test('users can logout', function () {
//     $user = User::factory()->create();

//     $response = $this->actingAs($user)->post('/logout');

//     $this->assertGuest();
//     $response->assertRedirect('/');
// });
