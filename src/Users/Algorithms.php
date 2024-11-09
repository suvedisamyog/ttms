<?php
namespace App\TTMS\Users;

use App\TTMS\Database\Operations\UserOperations;

class Algorithms {

	/**
	 * Trending algorithm to sort packages based on booking count and recency
	 */
    public static function trending_algo($packages) {
        $bookings = new UserOperations('bookings');
        $bookings_data = $bookings->get_all_data();


        $booking_counts = [];
        $recency_scores = [];
        $today = time();


        foreach ($bookings_data as $booking) {
            $package_id = $booking['package_id'];
            $booking_date = strtotime($booking['created_at']);

            if (!isset($booking_counts[$package_id])) {
                $booking_counts[$package_id] = 0;
            }
            $booking_counts[$package_id]++;


            $days_ago = ($today - $booking_date) / (60 * 60 * 24);
            $recency_score = max(0, 1 - $days_ago / 3);

            if (!isset($recency_scores[$package_id])) {
                $recency_scores[$package_id] = 0;
            }
            $recency_scores[$package_id] += $recency_score;
        }

        // Sort packages by booking count and recency score (combined)
        usort($packages, function($a, $b) use ($booking_counts, $recency_scores) {

            $bookings_a = isset($booking_counts[$a['id']]) ? $booking_counts[$a['id']] : 0;
            $recency_a = isset($recency_scores[$a['id']]) ? $recency_scores[$a['id']] : 0;


            $bookings_b = isset($booking_counts[$b['id']]) ? $booking_counts[$b['id']] : 0;
            $recency_b = isset($recency_scores[$b['id']]) ? $recency_scores[$b['id']] : 0;


            $score_a = $bookings_a + $recency_a * 100;
            $score_b = $bookings_b + $recency_b * 100;

            return $score_b - $score_a;
        });


        return $packages;
    }

	/**
	 * Recommendation algorithm to recommend packages based on user's booking history
	 */
	public static function recommendation_algo($packages , $all_categories ,$user_id){

		if(0 === $user_id){
			return array();
		}

		$bookings = new UserOperations('bookings');
		$bookings_data = $bookings->get_all_data([
			'where_clause' => 'user_id',
			'where_clause_value' => $user_id
		]);

		$user_booked_packages = [];
		foreach ($bookings_data as $booking) {
			$user_booked_packages[] = $booking['package_id'];
		}

		$recommended_packages = array();

		foreach($packages as $package){

			if (in_array($package['id'], $user_booked_packages)) {
				continue; //skip if already booked.
			}
			$max_similarity = 0;
			foreach ($user_booked_packages as $user_package_id) {
				$user_package = null;
				foreach ($packages as $pkg) {
					if ($pkg['id'] == $user_package_id) {
						$user_package = $pkg;
						break;
					}
				}
				if ($user_package) {
					$user_package_vector = self::package_to_vector($user_package, $all_categories);
					$package_vector = self::package_to_vector($package, $all_categories);

					$similarity = self::weighted_cosine_similarity($user_package_vector, $package_vector);

					if ($similarity > $max_similarity) {
						$max_similarity = $similarity;
					}
				}
			}
			if ($max_similarity > 0) {
				$recommended_packages[] = [
					'package' => $package,
					'similarity' => $max_similarity
				];
			}
		}
		 usort($recommended_packages, function ($a, $b) {
			return $b['similarity'] <=> $a['similarity'];
		});

		$recommended_packages = array_map(function ($item) {
			return $item['package'];
		}, $recommended_packages);
		return $recommended_packages;
	}

	/**
	 * Function to convert a package to a vector (with weighted categories and price)
	 */
    private static function package_to_vector($package, $all_categories) {
		$categories = is_string($package['category']) ? json_decode($package['category'], true) : $package['category'];
        $category_vector = [];

        foreach ($all_categories as $category) {
            $category_vector[] = in_array($category['id'], $categories) ? 1 : 0;
        }

        return array_merge([floatval($package['price'])], $category_vector);
    }

	/**
	 * Function to calculate weighted cosine similarity between two vectors
	 */
	public static function weighted_cosine_similarity($vector_a, $vector_b, $weight_category = 0.75, $weight_price = 0.25) {

			$dot_product = 0;
			$magnitude_a = 0;
			$magnitude_b = 0;

			for ($i = 1; $i < count($vector_a); $i++) {
				$dot_product += $vector_a[$i] * $vector_b[$i];
				$magnitude_a += $vector_a[$i] * $vector_a[$i];
				$magnitude_b += $vector_b[$i] * $vector_b[$i];
			}

			$dot_product += $weight_price * $vector_a[0] * $vector_b[0];
			$magnitude_a += $weight_price * $vector_a[0] * $vector_a[0];
			$magnitude_b += $weight_price * $vector_b[0] * $vector_b[0];

			$magnitude_a = sqrt($magnitude_a);
			$magnitude_b = sqrt($magnitude_b);

			if ($magnitude_a == 0 || $magnitude_b == 0) {
				return 0;
			}

			return $dot_product / ($magnitude_a * $magnitude_b);
	}
}
?>
