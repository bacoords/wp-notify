<?php

class WP_Notify_Recipient_Collection
	implements Iterator,
		Countable,
		JsonSerializable,
		WP_Notify_Json_Unserializable {

	/**
	 * Internal array of recipients.
	 *
	 * @var WP_Notify_Recipient[]
	 */
	protected $recipients = array();

	/**
	 * Instantiates a WPNotify_RecipientCollection object.
	 *
	 * @param array $recipients Array of recipients to instantiate the
	 *                          collection with.
	 */
	public function __construct( $recipients = array() ) {
		$this->recipients = $this->validate_recipients( $recipients );
	}

	/**
	 * Validate the recipients.
	 *
	 * @param WP_Notify_Recipient|array $recipients Recipient or array of
	 *                                                    recipients to
	 *                                                    validate.
	 *
	 * @return array Validated array of recipients.
	 *
	 * @throws WP_Notify_Failed_To_Add_Recipient If a recipient could not be added.
	 */
	protected function validate_recipients( $recipients ) {
		if ( ! is_array( $recipients ) ) {
			$recipients = array( $recipients );
		}

		foreach ( $recipients as $recipient ) {
			if ( ! $recipient instanceof WP_Notify_Recipient ) {
				throw WP_Notify_Failed_To_Add_Recipient::from_invalid_recipient( $recipient );
			}
		}

		return $recipients;
	}

	public function add( WP_Notify_Recipient $recipient ) {
		$this->recipients[] = $recipient;
	}

	public function count() {
		return count( $this->recipients );
	}

	/**
	 * Return the current recipient.
	 *
	 * @return WP_Notify_Recipient Recipient
	 */
	public function current() {
		return current( $this->recipients );
	}

	/**
	 * Move forward to next recipient
	 *
	 * @return void Any returned value is ignored.
	 */
	public function next() {
		next( $this->recipients );
	}

	/**
	 * Return the key of the current recipient
	 *
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key() {
		return key( $this->recipients );
	}

	/**
	 * Checks if current position is valid
	 *
	 * @return boolean The return value will be casted to boolean and then
	 *                 evaluated. Returns true on success or false on failure.
	 */
	public function valid() {
		// TODO: Implement valid() method.
	}

	/**
	 * Rewind the Iterator to the first recipient.
	 *
	 * @return void Any returned value is ignored.
	 */
	public function rewind() {
		reset( $this->recipients );
	}

	/**
	 * Specifies data which should be serialized to JSON.
	 *
	 * @return mixed Data which can be serialized by json_encode, which is a
	 *               value of any type other than a resource.
	 */
	public function jsonSerialize() {
		return $this->recipients;
	}

	/**
	 * Creates a new instance from JSON-encoded data.
	 *
	 * @param string $json JSON-encoded data to create the instance from.
	 *
	 * @return self
	 */
	public static function json_unserialize( $json ) {
		$recipients = json_decode( $json );

		return new self( $recipients );
	}
}
