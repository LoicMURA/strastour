const fetcher = {
    async fetchData(object, path, criterias){
        const query = await fetch(path);
        const datas = await query.json();

        function runSelection(datas, criteria) {
            for (const dataKey in datas) {
                if(dataKey == criteria) {
                    let data = datas[dataKey];
                    if(index < criterias.length - 1) {
                        index++;
                        runSelection(data, criterias[index]);
                    } else {
                        fetcher.hydrateData(data, object);
                    }
                }
            }
        }

        let index = 0;
        runSelection(datas, criterias[index]);
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