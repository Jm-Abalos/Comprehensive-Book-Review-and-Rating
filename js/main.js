document.addEventListener('DOMContentLoaded', function() {
    // Show/hide comments
    document.querySelectorAll('.show-comments').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const reviewId = this.dataset.reviewId;
            const commentsSection = document.getElementById(`comments-${reviewId}`);
            
            if (commentsSection.style.display === 'none' || !commentsSection.style.display) {
                fetchComments(reviewId);
                commentsSection.style.display = 'block';
                this.textContent = 'Hide Comments';
            } else {
                commentsSection.style.display = 'none';
                this.textContent = 'Show Comments';
            }
        });
    });

    // Post comment
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const reviewId = this.dataset.reviewId;
            const commentText = this.querySelector('textarea').value.trim();

            if (commentText === '') {
                alert('Comment cannot be empty.');
                return;
            }
            
            postComment(reviewId, commentText);
        });
    });
});

// Function to fetch and display comments for a specific review
function fetchComments(reviewId) {
    fetch(`get_comments.php?review_id=${reviewId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(comments => {
            const commentsSection = document.getElementById(`comments-${reviewId}`);
            commentsSection.innerHTML = ''; // Clear existing comments
            
            comments.forEach(comment => {
                const commentElement = document.createElement('div');
                commentElement.classList.add('comment');
                commentElement.innerHTML = `
                    <p><strong>${comment.username}</strong>: ${comment.comment_text}</p>
                    <small>${comment.created_at}</small>
                `;
                commentsSection.appendChild(commentElement);
            });
        })
        .catch(error => console.error('Error fetching comments:', error));
}

// Function to post a comment
function postComment(reviewId, commentText) {
    fetch('post_comment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `review_id=${reviewId}&comment_text=${encodeURIComponent(commentText)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(result => {
        console.log(result);  // For debugging
        if (result.success) {
            fetchComments(reviewId); // Fetch and display the updated list of comments
            document.querySelector(`form[data-review-id="${reviewId}"] textarea`).value = ''; // Clear the textarea
        } else {
            alert(result.message || 'Failed to post comment. Please try again.');
        }
    })
    .catch(error => console.error('Error posting comment:', error));
}
