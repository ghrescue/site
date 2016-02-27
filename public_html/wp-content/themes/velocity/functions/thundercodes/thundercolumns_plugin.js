
(function(){
		tinymce.create('tinymce.plugins.thundercolumns', {
	    createControl: function(n, cm) {
	        switch (n) {
	            case 'thundercolumns_button':
	                var c = cm.createSplitButton('thundercolumns_button', {
	                    title : 'themetasticColumns',
	                    image : '../wp-content/themes/velocity/functions/thundercodes/columns.png',
	                    
	                });
	
	                c.onRenderMenu.add(function(c, m) {
	                    m.add({title : 'themetasticColumns', 'class' : 'mceMenuItemTitle'}).setDisabled(1);
	
	                    m.add({title : '1/2', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[one_half]YOUR_TEXT_HERE[/one_half]');                    
	                    }});
	                    
	                    m.add({title : '1/2_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[one_half_last]YOUR_TEXT_HERE[/one_half_last]');                    
	                    }});
	                    
	                     m.add({title : '1/3', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[one_third]YOUR_TEXT_HERE[/one_third]');                    
	                    }});
	                    
	                    m.add({title : '1/3_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[one_third_last]YOUR_TEXT_HERE[/one_third_last]');                    
	                    }});
	                    
	                    m.add({title : '2/3', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[two_third]YOUR_TEXT_HERE[/two_third]');                    
	                    }});
	                    
	                    m.add({title : '2/3_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[two_third_last]YOUR_TEXT_HERE[/two_third_last]');                    
	                    }});
	                    
	                    m.add({title : '1/4', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[one_fourth]YOUR_TEXT_HERE[/one_fourth]');                    
	                    }});
	                    
	                    m.add({title : '1/4_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[one_fourth_last]YOUR_TEXT_HERE[/one_fourth_last]');                    
	                    }});
	                    
	                    m.add({title : '3/4', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[three_fourth]YOUR_TEXT_HERE[/three_fourth]');                    
	                    }});
	                    
	                    m.add({title : '3/4_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[three_fourth_last]YOUR_TEXT_HERE[/three_fourth_last]');                    
	                    }});
	                    
	                    m.add({title : '1/5', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[one_fifth]YOUR_TEXT_HERE[/one_fifth]');                    
	                    }});
	                    
	                    m.add({title : '1/5_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[one_fifth_last]YOUR_TEXT_HERE[/one_fifth_last]');                    
	                    }});
	                    
	                    m.add({title : '2/5', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[two_fifth]YOUR_TEXT_HERE[/two_fifth]');                    
	                    }});
	                    
	                    m.add({title : '2/5_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[two_fifth_last]YOUR_TEXT_HERE[/two_fifth_last]');                    
	                    }});
	                    
	                    m.add({title : '3/5', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[three_fifth]YOUR_TEXT_HERE[/three_fifth]');                    
	                    }});
	                    
	                    m.add({title : '3/5_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[three_fifth_last]YOUR_TEXT_HERE[/three_fifth_last]');                    
	                    }});
	                    
	                    m.add({title : '4/5', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[four-fifth]YOUR_TEXT_HERE[/four_fifth]');                    
	                    }});
	                    
	                    m.add({title : '4/5_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[four-fifth_last]YOUR_TEXT_HERE[/fifth_last]');                    
	                    }});
	                    
	                    m.add({title : '1/6', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[one_sixth]YOUR_TEXT_HERE[/one_sixth]');                    
	                    }});
	                    
	                    m.add({title : '1/6_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[one_sixth_last]YOUR_TEXT_HERE[/one_sixth_last]');                    
	                    }});
	                    
	                    m.add({title : '5/6', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[five_sixth]YOUR_TEXT_HERE[/five_sixth]');                    
	                    }});
	                    
	                    m.add({title : '5/6_last in Row', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[five_sixth_last]YOUR_TEXT_HERE[/five_sixth_last]');                    
	                    }});

	                    
	                });
	
	              // Return the new splitbutton instance
	              return c;
	        }
	
	        return null;
	    }
	});	
	
	
	
	tinymce.PluginManager.add('thundercolumns', tinymce.plugins.thundercolumns);
})()