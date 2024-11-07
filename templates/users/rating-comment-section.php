<?php
use App\TTMS\Database\Operations\UserOperations;
$current_user_id = get_current_user_attr('user_id');
$comments_query = new UserOperations('comments_and_ratings');
$comments = $comments_query->get_all_data([
	'where_clause' => 'package_id',
	'where_clause_value' => $package['id'],
]);

$user_comments = array_filter($comments, function($comment) use ($current_user_id) {
    return $comment['user_id'] == $current_user_id;
});
$comment = $user_comments[0]['comment'] ?? '';
$rating = $user_comments[0]['rating'] ?? 0 ;
$btn_id = (! empty ($user_comments) ) ? 'update_comment' : 'submit_comment' ;
$btn_name = (! empty ($user_comments) ) ? 'Update Comment' : 'Comment';
$show_delete = (! empty ($user_comments) ) ? '' : 'd-none';
$comment_id = $user_comments[0]['id'] ?? 0 ;

?>
<div class="container my-4">
	<h5>Leave a Comment & Rating</h5>
		<!-- Star Rating -->
		<div class="mb-3">
			<label for="rating" class="form-label">Rate:</label>
			<div id="rating" class="d-flex">
				<?php
				echo '<div class="text-warning">';
				for ($i = 1; $i <= 5; $i++) {
					echo ($i <= $rating)
						? '<i class="bi bi-star-fill text-warning" data-rating="' . $i . '"></i>'
						: '<i class="bi bi-star text-warning" data-rating="' . $i . '"></i>';
				}
				echo '</div>';
				?>
			</div>
		</div>

		<!-- Comment Textarea -->
		<div class="mb-3">
			<label for="comment" class="form-label">Comment:</label>
			<textarea class="form-control" id="comment" rows="3" required><?php echo $comment ?></textarea>
			<span class="text-danger m-2" id="comment-error"></span>
		</div>
		<input type="hidden" id="package_id"  value="<?php echo $package['id'] ?>">
		<input type="hidden" id="comment_id"  value="<?php echo $comment_id ?>">
		<!-- Submit Button -->
		 <div class="flex">
			 <button id="<?php echo $btn_id ?>" class="btn btn-warning btn-sm "><?php echo $btn_name  ?></button>
			 <button data-id="<?php echo $comment_id  ?>" data-action="comments_and_ratings" id="delete_comment" class="btn btn-danger btn-sm delete_btn <?php echo $show_delete ?> ">Delete Comment</button>

		 </div>

	<!-- Display Comments -->
	<div id="commentsSection" class="mt-4">
    <h6>Comments</h6>
	<?php
	if( empty ( $comments ) ){
		echo '<div class="text-danger">No comments found</div>';
		return ;
	}
	?>
    <div id="commentsList" class="overflow-auto border p-3 card" style="max-height: 100vh;">
        <?php
			foreach ($comments as $comment) {
                $rating = $comment['rating'];
                $filledStars = str_repeat('<i class="bi bi-star-fill text-warning"></i>', $rating);
                $unfilledStars = str_repeat('<i class="bi bi-star text-warning"></i>', 5 - $rating);
				$users = new UserOperations('users');
                $author = $users->get_individual_data_from_id((int) $comment['user_id'] );
                $author_name = $author['username'] ?? 'Unknown';

                echo '
                <div class="comment-box border-bottom pb-3 mb-3 card card-pop p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">User ' . htmlspecialchars($author_name) . '</span>
                        <span>' . $filledStars . $unfilledStars . '</span>
                    </div>
                    <p class="mb-1">' . htmlspecialchars($comment['comment']) . '</p>
                    <small class="text-muted">Posted on ' . htmlspecialchars(date('F j, Y, g:i a', strtotime($comment['created_at']))) . '</small>
                </div>';
            }
        ?>
    </div>
</div>

</div>
