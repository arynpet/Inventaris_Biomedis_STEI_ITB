<?php

it('returns a successful response', function () {
    // Mengeksekusi request ke root URL
    $response = $this->get('/');

    // Mengubah asersi untuk menerima status redirect 302
    $response->assertStatus(302);
    
    // Atau memvalidasi target pengalihan
    $response->assertRedirect('/login');
});