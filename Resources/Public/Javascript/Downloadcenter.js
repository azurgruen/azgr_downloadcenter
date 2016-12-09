var Downloadcenter = (function($) {
	
	'use strict';
	
	var
	buildurl,
	domcontainer = '.downloadcenter', // css selectors below are children of this container
	cookiename = 'azgrdlc_files',
	el = {},
	css = {
		classes : {
			hidden : 'hidden',
			active : 'active'
		},
		selectors : {
			filecollection : '.filecollection',
			submit : '.filecollection .init',
			basket : '.basket .items',
			basketlabel : '.basket .label',
			basketcount : '.basket .count',
			panelswitches : '.panel-switch',
			panels : '.panel',
			tabnavitems : '.tab-nav *',
			tabs : '.tab-content > *',
			files : '.file'
		}
	},
	panel = {
		dataAttr : 'pid'
	},
	tab = {
		dataAttr : {
			uid : 'tid',
			label : 'group'
		}
	},
	basket = {
		itemTag : 'span',
		itemAttr : 'data-id',
		itemLabel : '.title', // selector of file element
		groupTag : 'div',
		dataAttr : 'id', //same as file data-*
		files : [],
		filetitles : []
	},
	file = {},
	init = function() {
		var cookiedata = Cookies.getJSON(cookiename);
		// preselect files (cookie)
		if (cookiedata !== undefined) {
			cookiedata.forEach(function(id) {
				basket.add(id);
			});
		}
		// preselect files (url)
		if (location.hash) {
			var active = location.hash.substr(1).split(','),
				pid = file.getPanel(active[0]),
				tid = file.getTab(active[0]);
			panel.set(pid);
			tab.set(tid);
			active.forEach(function(id) {
				basket.add(parseInt(id));
			});
		// just open first panel and tab
		} else {
			panel.active = el.panels.first();
			tab.active = el.tabs.first();
			el.panelswitches.first().addClass(css.classes.active);
			panel.active.addClass(css.classes.active);
			el.tabnavitems.first().addClass(css.classes.active);
			tab.active.first().addClass(css.classes.active);
		}
	},
	setBuildUrl = function(url) {
		buildurl = url;
	},
	setFormId = function(id) {
		formid = id;
	};
	
	el.populate = function map(obj, sub) {
		var that = this;
		sub = typeof sub !== 'undefined' ? sub : null;
		if (typeof that[sub] === 'undefined') that[sub] = {};
		for (var selector in obj) {
			if (obj.hasOwnProperty(selector)) {
				if (typeof obj[selector] === 'object') {
					map(obj[selector], selector);
				} else {
					if (sub) {
						that[sub][selector] = $(domcontainer+' '+obj[selector]);
					} else {
						that[selector] = $(domcontainer+' '+obj[selector]);
					}
				}
			}
		}
	};
	
	el.init = function() {
		this.window = $(window);
		this.html = $('html');
		this.populate(css.selectors);
	};
	
	panel.set = function(id) {
		var filter = '[data-'+panel.dataAttr+'="'+id+'"]';
		this.active = el.panels.filter(filter);
		el.panelswitches.removeClass(css.classes.active);
		el.panels.removeClass(css.classes.active);
		el.panelswitches.filter(filter).addClass(css.classes.active);
		this.active.addClass(css.classes.active);
		// set first tab when no files are preselected
		if (
			!location.hash && 
			!this.active.find(css.selectors.tabs+'.'+css.classes.active).length
		) {
			tab.set(this.active.find(css.selectors.tabs).first().data(tab.dataAttr.uid));
		}
	};
	
	tab.set = function(id) {
		var tabs = panel.active.find(css.selectors.tabs),
			tabnavitems = panel.active.find(css.selectors.tabnavitems);
		this.active = tabs.filter('[data-'+tab.dataAttr.uid+'="'+id+'"]');
		tabs.removeClass(css.classes.active);
		this.active.addClass(css.classes.active);
		tabnavitems.removeClass(css.classes.active);
		tabnavitems.filter('[data-'+tab.dataAttr.uid+'="'+id+'"]').addClass(css.classes.active);
	};
	
	basket.hasItem = function(id) {
		if (el.basket.find('['+basket.itemAttr+'="'+id+'"]').length > 0) {
			return true;
		} else {
			return false;
		}
	};
	
	basket.isEmpty = function() {
		return el.basket.is(':empty');
	};
	
	basket.getItems = function() {
		return el.basket.find(basket.itemTag);
	};
	
	basket.countItems = function() {
		return this.getItems().length;
	};
	
	basket.add = function(id) {
		var file = el.files.filter('[data-'+basket.dataAttr+'="'+id+'"]'),
			groupId = file.parent().data(tab.dataAttr.uid),
			groupLabel = file.parent().data(tab.dataAttr.label),
			group = el.basket.find(basket.groupTag+'[data-id="'+groupId+'"]'),
			title = file.find(basket.itemLabel).first().text().trim(),
			item = '<'+basket.itemTag+' '+basket.itemAttr+'="'+id+'" data-group="'+groupId+'">'+title+'</'+basket.itemTag+'>';
		if (basket.isEmpty()) {
			el.filecollection.addClass(css.classes.active);
		}
		// wrap items in groups
		if (!group.length) {
			el.basket.append(item);
			el.basket
				.find('[data-group="'+groupId+'"]')
				.wrapAll('<'+basket.groupTag+' data-id="'+groupId+'" label="'+groupLabel+'" />');
		} else {
			group.append(item);
		}
		basket.files.push(id);
		basket.filetitles.push(title);
		Cookies.set(cookiename, JSON.stringify(basket.files));
		el.basketcount.text(basket.countItems());
		el.files.filter('[data-'+basket.dataAttr+'="'+id+'"]').addClass(css.classes.active);
	};
	
	basket.remove = function(id) {
		var item = el.basket.find('['+basket.itemAttr+'="'+id+'"]'),
			fileindex = basket.files.indexOf(id);
		if (!item.siblings().length) {
			item.closest(basket.groupTag).remove();
		}
		item.remove();
		if (fileindex !== -1) {
			basket.files.splice(fileindex, 1);
		}
		el.basketcount.text(basket.countItems());
		el.files.filter('[data-'+basket.dataAttr+'="'+id+'"]').removeClass(css.classes.active);
		if (basket.isEmpty()) {
			Cookies.remove(cookiename);
			el.filecollection.removeClass(css.classes.active);
		} else {
			Cookies.set(cookiename, JSON.stringify(basket.files));
		}
	};
	
	file.get = function(id) {
		var $file = el.files.filter('[data-'+basket.dataAttr+'="'+id+'"]');
		return $file;
	};
	
	file.getPanel = function(id) {
		var $file = file.get(id),
			pid = $file.closest(css.selectors.panels).data(panel.dataAttr);
		return pid;
	};
	
	file.getTab = function(id) {
		var $file = file.get(id),
			tid = $file.closest('[data-'+tab.dataAttr.uid+']').data(tab.dataAttr.uid);
		return tid;
	};
	
	
	$(document).ready(function() {
		
		el.init();
		init();
		
		el.panelswitches.on('click', function(e) {
			e.preventDefault();
			panel.set($(this).data(panel.dataAttr));
		});
		
		el.tabnavitems.on('click', function(e) {
			e.preventDefault();
			tab.set($(this).data(tab.dataAttr.uid));
		});
		
		el.tabs.on('click', css.selectors.files, function(e) {
			e.preventDefault();
			var id = $(this).data(basket.dataAttr);
			if (basket.hasItem(id)) {
				basket.remove(id);
			} else {
				basket.add(id);
			}
		});
		
		el.basketlabel.on('click', function(e) {
			e.preventDefault();
			el.basket.toggleClass(css.classes.active);
		});
		
		el.basket.on('click', basket.itemTag, function(e) {
			e.preventDefault();
			basket.remove($(this).data('id'));
		});
		
/*
		el.submit.one('click', function(e) {
			//if ($(this).data('preventDefault') === undefined) {
				e.preventDefault();
				if (basket.files !== '') {
					//$(this).prop('disabled', true);
					var that = this,
						request = $.ajax({
						url : buildurl+'&tx_azgrdownloadcenter_zip[files]='+basket.files.join(',')
					});
					request.done(function(data, textStatus, jqXHR) {
						console.log(data);
						$('#powermail_field_downloadlink').val(data.file);
						$('#powermail_field_dateien').val(basket.filetitles.join(','));
						$(that).data('preventDefault', false);
						//that.click();
					});
				}
			//}
		});
*/
		
		$('form').autoform({
			idAttr : 'name',
			expires : 365 // int days
		});
	});
	
	return { collection : basket.files, setBuildUrl : setBuildUrl };
	
})(jQuery);