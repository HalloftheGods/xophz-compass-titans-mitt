<?php

/**
 * The REST API functionality of the plugin.
 *
 * @package    Xophz_Compass_Titans_Mitt
 * @subpackage Xophz_Compass_Titans_Mitt/includes
 */
class Xophz_Compass_Titans_Mitt_REST {

	public function register_routes() {
		register_rest_route( 'compass/v1', '/titans-mitt/stats', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_stats' ),
			'permission_callback' => array( $this, 'permissions_check' ),
		) );

		register_rest_route( 'compass/v1', '/titans-mitt/smush', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'trigger_bulk_smush' ),
			'permission_callback' => array( $this, 'permissions_check' ),
		) );
	}

	public function permissions_check() {
		return current_user_can( 'manage_options' );
	}

	public function get_stats( WP_REST_Request $request ) {
		if ( ! class_exists( 'WP_Smush' ) ) {
			return new WP_Error( 'missing_smush', 'WP Smush is not active.', array( 'status' => 500 ) );
		}

		$core = \WP_Smush::get_instance()->core();
        
        // Ensure stats setup is initialized to get accurate unsmushed accounts
        if ( method_exists( $core, 'setup_global_stats' ) ) {
            $core->setup_global_stats();
        }

		$global_stats = $core->get_global_stats();
		
		$unsmushed = $core->get_unsmushed_attachments();
        $unsmushed = ! empty( $unsmushed ) && is_array( $unsmushed ) ? array_values( $unsmushed ) : array();

		// Resmush ids
		$resmush_ids = method_exists( $core, 'get_resmush_ids' ) ? $core->get_resmush_ids() : array();

		return rest_ensure_response( array(
			'global_stats' => $global_stats,
            'summary' => array(
                'total_count'   => count( $core->get_media_attachments() ),
                'smushed_count' => count( $core->get_smushed_attachments() ),
                'remaining'     => count( $unsmushed ),
                'resmush_count' => count( $resmush_ids )
            ),
			'unsmushed'    => $unsmushed,
			'resmush_ids'  => $resmush_ids
		) );
	}

	public function trigger_bulk_smush( WP_REST_Request $request ) {
		if ( ! class_exists( 'WP_Smush' ) ) {
			return new WP_Error( 'missing_smush', 'WP Smush is not active.', array( 'status' => 500 ) );
		}

		$attachment_id = $request->get_param( 'attachment_id' );

		if ( empty( $attachment_id ) ) {
			return new WP_Error( 'missing_attachment_id', 'Missing attachment_id parameter.', array( 'status' => 400 ) );
		}

		$smush = \WP_Smush::get_instance()->core()->mod->smush;
		$meta = '';
		$errors = new \WP_Error();

		$smush->smushit( (int) $attachment_id, $meta, $errors );

		if ( $errors && $errors->has_errors() ) {
            $error_msg = $errors->get_error_message();
			return new WP_Error( 'smush_failed', $error_msg, array( 'status' => 500 ) );
		}

		// Update the bulk Limit count
		\Smush\Core\Core::update_smush_count();

		return rest_ensure_response( array(
			'success'       => true,
			'attachment_id' => $attachment_id,
			'message'       => 'Smushed successfully'
		) );
	}
}
