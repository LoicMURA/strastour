const fetcher = {
    async fetchData(object, path,id){
        const query = await fetch(`/assets/datas/${path}`)
        const datas = await query.json();
        for (const dataKey in datas) {
            if(dataKey === id){
                let data = datas[dataKey];
                this.hydrateData(data, object);
            }
        }
    },
    hydrateData(data, object){
        for(const dataProperty in data){
            for(const objectProperty in object){
                if(dataProperty === objectProperty){
                    object[objectProperty] = data[dataProperty];
                }
            }
        }
    }
}

export {fetcher};