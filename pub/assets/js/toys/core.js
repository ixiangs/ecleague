Toys = {};
Toys.Locale = {
	languages : {},
	current : null,
	use : function(lang) {
		Toys.Locale.current = lang;
	},
	define : function(lang, group, texts) {
		if (!Object.keys(Toys.Locale.languages).contains(lang)) {
			Toys.Locale.languages[lang] = {};
		}

		Toys.Locale.languages[lang][group] = texts;
	},
	get : function(name) {
		var arr = name.split('.');
		var cur = Toys.Locale.current;
		if(Toys.Locale.languages[cur]){
			if(Toys.Locale.languages[cur][arr[0]]){
				if(Toys.Locale.languages[cur][arr[0]][arr[1]]){
					return Toys.Locale.languages[cur][arr[0]][arr[1]];
				}
			}
		}
		return '';
	},
};

