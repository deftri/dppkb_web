<?php
$halaman = 'faq.php';
include '../includes/header.php';
?>

<!-- Konten FAQ -->
<h2>Frequently Asked Questions (FAQ)</h2>
<div class="accordion" id="faqAccordion">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" 
                aria-expanded="true" aria-controls="collapseOne">
                Pertanyaan 1
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Jawaban untuk pertanyaan 1.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" 
                aria-expanded="false" aria-controls="collapseTwo">
                Pertanyaan 2
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
                Jawaban untuk pertanyaan 2.
            </div>
        </div>
    </div>
    <!-- Tambahkan lebih banyak pertanyaan sesuai kebutuhan -->
</div>

<?php
include '../includes/footer.php';
?>
