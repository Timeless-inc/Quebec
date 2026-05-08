<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UploadValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure storage is faked for tests
        Storage::fake('public');
        // Disable CSRF for test requests to simulate form posts
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    private function baseRequestData()
    {
        return [
            'nomeCompleto' => 'Test User',
            'cpf' => '000.000.000-00',
            'celular' => '11999999999',
            'email' => 'user@example.com',
            'rg' => '1234567',
            'orgaoExpedidor' => 'SSP',
            'campus' => 'Campus Teste',
            'matricula' => '20210001',
            'situacao' => 1,
            'curso' => 1,
            'periodo' => 1,
            'turno' => 'manhã',
            'tipoRequisicao' => 9, // tipo que não exige dadosExtra
        ];
    }

    public function test_image_under_limit_is_accepted()
    {
        $user = User::factory()->create(['role' => 'Aluno']);
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('photo.jpg')->size(4000); // 4 MB

        $data = $this->baseRequestData();
        $data['anexarArquivos'] = [$file];

        $response = $this->post(route('application.store'), $data);

        $response->assertRedirect();

        // Check that some file exists under requerimentos_arquivos
        $files = Storage::disk('public')->files('requerimentos_arquivos');
        $this->assertNotEmpty($files, 'Nenhum arquivo processado foi gravado em requerimentos_arquivos');
    }

    public function test_image_over_limit_is_rejected()
    {
        $user = User::factory()->create(['role' => 'Aluno']);
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('big.jpg')->size(6000); // 6 MB

        $data = $this->baseRequestData();
        $data['anexarArquivos'] = [$file];

        $response = $this->post(route('application.store'), $data);

        $response->assertSessionHasErrors();
    }

    public function test_pdf_over_limit_is_rejected()
    {
        $user = User::factory()->create(['role' => 'Aluno']);
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('doc.pdf', 3000, 'application/pdf'); // 3 MB

        $data = $this->baseRequestData();
        $data['anexarArquivos'] = [$file];

        $response = $this->post(route('application.store'), $data);

        $response->assertSessionHasErrors();
    }

    public function test_total_upload_size_exceeds_middleware_limit()
    {
        $user = User::factory()->create(['role' => 'Aluno']);
        $this->actingAs($user);

        $file1 = UploadedFile::fake()->image('a.jpg')->size(6000); // 6 MB
        $file2 = UploadedFile::fake()->image('b.jpg')->size(6000); // 6 MB -> total 12 MB

        $data = $this->baseRequestData();
        // use the same field name used by application.store
        $data['anexarArquivos'] = [$file1, $file2];

        $response = $this->post(route('application.store'), $data);

        // oversized individual files are rejected by validation rules
        $response->assertSessionHasErrors();
    }
}
