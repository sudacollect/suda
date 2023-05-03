document.addEventListener('alpine:init', () => {
    Alpine.store('menuStyle', {
        name: '',
    })

    Alpine.data('userDropdown', () => ({
        open: false,

        toggleUserDropdown() {
            this.open = ! this.open
        },
    }))

    Alpine.data('sudaBody', () => ({
        proSidebar: false,
        sidebarStyle: '',
        toggleSidebar(e) {
            e.preventDefault()
            this.proSidebar = !this.proSidebar
            if(e.currentTarget.parentElement.classList.contains('navbar-suda-icon')){
                e.currentTarget.parentElement.classList.remove('navbar-suda-icon')
                this.$refs.sidebar.classList.remove('press-sidebar-icon')
                this.$refs.sidebar.classList.add('in')
                this.$refs.sidebar.classList.add('show')
                this.$refs.suda_app_content.classList.remove('suda-flat-lg')
                this.sidebarStyle = 'flat'
            }else if(!e.currentTarget.parentElement.classList.contains('navbar-suda-icon')){
                e.currentTarget.parentElement.classList.add('navbar-suda-icon')
                this.$refs.sidebar.classList.add('press-sidebar-icon')
                this.$refs.sidebar.classList.remove('in')
                this.$refs.sidebar.classList.remove('show')
                this.$refs.suda_app_content.classList.add('suda-flat-lg')
                this.sidebarStyle = 'icon'
            }
            this.$store.menuStyle.name = this.sidebarStyle
            // fetch(e.currentTarget.dataset.href, {
			// 	method: 'POST',
			// 	headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
            //     },
            //     body: JSON.stringify({
            //         style: this.sidebarStyle
            //     })
            // })
			// .then(() => {
            //     // this.message = 'Form sucessfully submitted!'
            //     this.$store.menuStyle.name = this.sidebarStyle
			// })
			// .catch(() => {
			// 	// this.message = 'Ooops! Something went wrong!'
			// })
        },
    }))
    

})




