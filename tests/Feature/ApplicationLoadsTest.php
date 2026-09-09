<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationLoadsTest extends TestCase
{
    public function test_the_login_page_is_available(): void
    {
        $this->get('/login')->assertOk();
    }
}
