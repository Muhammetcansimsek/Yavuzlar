<?php
include '../layout/header.php';
include './db.php';
session_start();

if (isset($_GET['n']) && $_GET['n'] == 1) {
    $_SESSION['score'] = 0;
}

$question_number = isset($_GET['n']) ? (int) $_GET['n'] : 1;

$query = "SELECT * FROM questions WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $question_number]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$question) {
    header('Location: final.php');  
    exit();
}

$answers = json_decode($question['answers'], true);
 
?>  

<main>
    <div class="container">
        <h2><?php echo $question['question']; ?></h2>
        <?php if (is_array($answers) && !empty($answers)) : ?>
            <form id="quizForm" method="post" action="process.php?n=<?php echo $question_number; ?>">
                <ul class="choices">
                    <?php foreach ($answers as $i => $answer) : ?>
                        <li data-choice="<?php echo $i + 1; ?>"><?php echo $answer; ?></li>
                    <?php endforeach; ?>
                </ul>
                <input type="hidden" name="choice" id="selectedChoice" />
                <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>" />
                <button type="submit">Soruyu İşaretle</button>
            </form>
        <?php else : ?>
            <p>Yanıtlar düzgün formatta değil veya boş!</p>
        <?php endif; ?>
    </div>
</main>

<script>
    document.querySelectorAll('.choices li').forEach(function(choice) {
        choice.addEventListener('click', function() {
            document.querySelectorAll('.choices li').forEach(function(c) {
                c.classList.remove('selected');
            });
            this.classList.add('selected');
            document.getElementById('selectedChoice').value = this.getAttribute('data-choice');
            document.querySelector('button[type="submit"]').removeAttribute('disabled');
        });
        
    });
</script>

<?php include '../layout/footer.php'; ?>