import { defineStore } from 'pinia'

export const useDomainStore = defineStore('domainStore', {
    state: () => ({
        domains: [] as Array<any>
    }),
    actions: {
        addDomain(domain: any) {
            this.domains.push(domain)
            localStorage.setItem('domains', JSON.stringify(this.domains))
        },
        loadDomains() {
            const data = localStorage.getItem('domains')
            if (data) this.domains = JSON.parse(data)
        }
    }
})