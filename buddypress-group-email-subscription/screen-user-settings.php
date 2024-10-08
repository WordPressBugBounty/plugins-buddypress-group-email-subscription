<?php
/**
 * GES code meant to be run only on a user's "Settings > Email" page.
 *
 * @since 3.7.0
 */

/**
 * Adds forum notification options in the user settings->notifications page.
 *
 * @return void
 */
function ass_group_subscription_notification_settings() {
	// get forum type
	$forums = ass_get_forum_type();

	// no forums installed? stop now!
	if ( ! $forums ) {
		return;
	}

	?>

	<table class="notification-settings zebra" id="groups-subscription-notification-settings">
	<thead>
		<tr>
			<th class="icon"><span class="bp-screen-reader-text"><?php esc_html_e( 'Item icon', 'buddypress-group-email-subscription' ); ?></span></th>
			<th class="title"><?php esc_html_e( 'Group Forum', 'buddypress-group-email-subscription' ); ?></th>
			<th class="yes"><?php esc_html_e( 'Yes', 'buddypress-group-email-subscription' ); ?></th>
			<th class="no"><?php esc_html_e( 'No', 'buddypress-group-email-subscription' ); ?></th>
		</tr>
	</thead>
	<tbody>

	<?php

	// only add the following options if BP's bundled forums are installed...
	// @todo add back these options for bbPress if possible.

	if ( 'buddypress' === $forums ) :
		$replies_to_topic = bp_get_user_meta( bp_displayed_user_id(), 'ass_replies_to_my_topic', true );
		if ( ! $replies_to_topic ) {
			$replies_to_topic = 'yes';
		}

		$replies_after_me = bp_get_user_meta( bp_displayed_user_id(), 'ass_replies_after_me_topic', true );
		if ( ! $replies_after_me ) {
			$replies_after_me = 'yes';
		}

		?>

		<tr>
			<td></td>
			<td><?php esc_html_e( 'A member replies in a forum topic you\'ve started', 'buddypress-group-email-subscription' ); ?></td>

			<td class="yes">
				<input type="radio" name="notifications[ass_replies_to_my_topic]" id="notification-ass-replies-to-my-topic-yes" value="yes" <?php checked( $replies_to_topic, 'yes', true ); ?>/>
				<label class="bp-screen-reader-text" for="notification-ass-replies-to-my-topic-yes"><?php esc_html_e( 'Yes, send email', 'buddypress-group-email-subscription' ); ?></label>
			</td>

			<td class="no">
				<input type="radio" name="notifications[ass_replies_to_my_topic]" value="no" id="notification-ass-replies-to-my-topic-no" <?php checked( $replies_to_topic, 'no', true ); ?>/>
				<label class="bp-screen-reader-text" for="notification-ass-replies-to-my-topic-no"><?php esc_html_e( 'Yes, send email', 'buddypress-group-email-subscription' ); ?></label>
			</td>
		</tr>

		<tr>
			<td></td>
			<td><?php esc_html_e( 'A member replies after you in a forum topic', 'buddypress-group-email-subscription' ); ?></td>

			<td class="yes">
				<input type="radio" name="notifications[ass_replies_after_me_topic]" id="notification-ass-replies-after-me-yes" value="yes" <?php checked( $replies_after_me, 'yes', true ); ?>/>
				<label class="bp-screen-reader-text" for="notification-ass-replies-after-me-no"><?php esc_html_e( 'Yes, send email', 'buddypress-group-email-subscription' ); ?></label>
			</td>

			<td class="no">
				<input type="radio" name="notifications[ass_replies_after_me_topic]" id="notification-ass-replies-after-me-no" value="no" <?php checked( $replies_after_me, 'no', true ); ?>/>
				<label class="bp-screen-reader-text" for="notification-ass-replies-after-me-yes"><?php esc_html_e( 'No, do not send email', 'buddypress-group-email-subscription' ); ?></label>
			</td>
		</tr>

	<?php endif; ?>

		<tr>
			<td></td>
			<td><?php esc_html_e( 'Receive notifications of your own posts?', 'buddypress-group-email-subscription' ); ?></td>

			<td class="yes">
				<input type="radio" name="notifications[ass_self_post_notification]" id="notification-ass-self-post-yes" value="yes" <?php checked( ass_self_post_notification( bp_displayed_user_id() ) ); ?> />
				<label class="bp-screen-reader-text" for="notification-ass-self-post-yes"><?php esc_html_e( 'No, do not send email', 'buddypress-group-email-subscription' ); ?></label>
			</td>

			<td class="no">
				<input type="radio" name="notifications[ass_self_post_notification]" id="notification-ass-self-post-no" value="no" <?php checked( ! ass_self_post_notification( bp_displayed_user_id() ) ); ?> />
				<label class="bp-screen-reader-text" for="notification-ass-self-post-no"><?php esc_html_e( 'No, do not send email', 'buddypress-group-email-subscription' ); ?></label>
			</td>
		</tr>

		<?php do_action( 'ass_group_subscription_notification_settings' ); ?>
		</tbody>
	</table>

	<?php
}
add_action( 'bp_notification_settings', 'ass_group_subscription_notification_settings' );

/**
 * Adds a notice at end of email notifictaion about how to change group email subscriptions.
 *
 * @return void
 */
function ass_add_notice_to_notifications_page() {
	$user_groups_link = bp_loggedin_user_url( bp_members_get_path_chunks( array( bp_get_groups_slug() ) ) );

	?>
		<div id="group-email-settings">
			<table class="notification-settings zebra">
				<thead>
					<tr>
						<th class="icon">&nbsp;</th>
						<th class="title"><?php esc_html_e( 'Individual Group Email Settings', 'buddypress-group-email-subscription' ); ?></th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>&nbsp;</td>
						<td>
							<?php // translators: 'My Groups' link ?>
							<p><?php printf( esc_html__( 'To change the email notification settings for your groups, go to %s and click "Change" for each group.', 'buddypress-group-email-subscription' ), '<a href="' . esc_url( $user_groups_link ) . '">' . esc_html__( 'My Groups', 'buddypress-group-email-subscription' ) . '</a>' ); ?></p>

							<?php if ( 'yes' === get_option( 'ass-global-unsubscribe-link' ) ) : ?>
								<p><a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'ass_unsubscribe', 'all' ), 'ass_unsubscribe_all' ) ); ?>"><?php esc_html_e( "Or set all your group's email options to No Email", 'buddypress-group-email-subscription' ); ?></a></p>
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php
}
add_action( 'bp_notification_settings', 'ass_add_notice_to_notifications_page', 9000 );
