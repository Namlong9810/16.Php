document.addEventListener("DOMContentLoaded", function() {
    const questionContainer = document.getElementById('question-container');
    const nextButton = document.getElementById('next-button');
    let currentScore = 0; // Khai báo điểm ban đầu
    let answeredQuestions = 0; // Khai báo số lượng câu hỏi đã được trả lời
    let currentAnswered = 1; // Khai báo câu hỏi hiện tại.

    function loadQuestion() {
        fetch('game/game.php?answered_questions=' + answeredQuestions)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    questionContainer.innerHTML = '<p>' + data.error + '</p>';
                } else {
                    questionContainer.innerHTML = `
                        <p>Câu hỏi số ${currentAnswered}: ${data.question}</p>
                        <button class="option-btn" data-option="A">${data.option_a}</button>
                        <button class="option-btn" data-option="B">${data.option_b}</button><br>
                        <button class="option-btn" data-option="C">${data.option_c}</button>
                        <button class="option-btn" data-option="D">${data.option_d}</button>
                    `;
                    // Bắt sự kiện click cho các nút đáp án
                    const optionButtons = document.querySelectorAll('.option-btn');
                    optionButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const selectedOption = this.getAttribute('data-option');
                            checkAnswer(selectedOption, data.correct_option);
                        });
                    });
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                questionContainer.innerHTML = '<p>Có lỗi xảy ra khi tải câu hỏi.</p>';
            });
    }

    function checkAnswer(selectedOption, correctOption) {
        if (selectedOption === correctOption) {
            alert('Chính xác!');
            currentScore += 1; // Trả lời đúng, tăng điểm
            currentAnswered +=1; // Tăng lên câu hỏi tiếp theo
            answeredQuestions++; // Tăng số lượng câu hỏi đã trả lời
        } else {
            alert('Sai rồi!');
            currentScore = 0; // Trả lời sai, đặt lại điểm
            currentAnswered = 1; // Đặt lại số câu hỏi về ban đầu
            answeredQuestions = 0; // Đặt lại số lượng câu hỏi đã trả lời được
        }
        loadQuestion(); // Tải câu hỏi tiếp theo
    }

    nextButton.addEventListener('click', loadQuestion);

    loadQuestion();
});