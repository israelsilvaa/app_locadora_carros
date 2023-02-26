<template>
    <div>

        <table class="table table-hover">
            <thead>
                <tr >
                    <th scope="col" v-for="t,key in titulos" :key="key">{{ t }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj in dados" :key="obj.id">
                    <td v-if="titulos.includes(chave)" v-for="valor, chave in obj" :key="chave" >
                        <span v-if="chave == 'imagem'">
                            <img :src="'/storage/'+valor" alt="" width="40" height="40">
                        </span>
                        <span v-else>
                            {{ valor }}
                        </span>
                    </td>

                    <!-- <th scope="row">{{ dado.id }}</th>
                    <th scope="row">{{ dado.nome }}</th>
                    <th scope="row"> <img :src="'/storage/'+dado.imagem" alt="" width="40" height="40"></th>  -->
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ['dados', 'titulos']
}
</script>
