{* reviews.tpl *}
{load_presentation_object filename="reviews" assign="obj"}
{if $obj->mTotalReviews != 0}
<h2>Customer reviews:</h2>
<ul class="reviews-list">
  {section name=i loop=$obj->mReviews}
  <li>
    <p>
      Review by <strong>{$obj->mReviews[i].name}</strong> on
      {$obj->mReviews[i].created_on|date_format:"%A, %B %e, %Y"}
    </p>
    <p>{$obj->mReviews[i].review}</p>
    <p>Rating: [{$obj->mReviews[i].rating} of 5]</p>
  </li>
  {/section}
</ul>
{else}
<h2>Be the first person to voice your opinion!</h2>
{/if}
{if $obj->mEnableAddProductReviewForm}
{* add review form *}
<h2>Add a review:</h2>
<form method="post" action="{$obj->mLinkToProduct}">
  <table class="review-table">
    <tr class="add-review">
      <td>From: <strong>{$obj->mReviewerName}</strong></td>
    </tr>
    <tr>
      <td>
        <textarea name="review"
         rows="3" cols="65">[Add your review here]</textarea>
      </td>
    </tr>
    <tr>
      <td class="add-review">
        <table class="review-table">
          <tr>
            <td>
              Your Rating:
              <input type="radio" name="rating" value="1" /> 1
              <input type="radio" name="rating" value="2" /> 2
              <input type="radio" name="rating" value="3" checked="checked" /> 3
              <input type="radio" name="rating" value="4" /> 4
              <input type="radio" name="rating" value="5" /> 5
            </td>
            <td align="right">
              <input type="submit" name="AddProductReview" value="Add review" />
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
{else}
<p>You must log in to add a review.</p>
{/if}
