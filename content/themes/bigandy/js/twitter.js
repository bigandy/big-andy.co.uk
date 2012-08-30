		new TWTR.Widget({
		  version: 2,
		  type: 'profile',
		  rpp: 4,
		  interval: 6000,
		  width: 250,
		  height: 300,
		  theme: {
			shell: {
			  background: '#00bef3',
			  color: '#ffffff'
			},
			tweets: {
			  background: '#ffffff',
			  color: '#000000',
			  links: '#00bef3'
			}
		  },
		  features: {
			scrollbar: false,
			loop: false,
			live: false,
			hashtags: true,
			timestamp: true,
			avatars: false,
			behavior: 'all'
		  }
		}).render().setUser('bigandy').start();