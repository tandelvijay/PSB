<template>
    <!--<div :class="openmodal">-->

        <!--&lt;!&ndash;<h1>Testing</h1>&ndash;&gt;-->
        <!--<li style="list-style: none">-->
            <!--<label class="column is-2"></label> {{ list.title }}-->
        <!--</li>-->

        <!--<li style="list-style: none">-->
            <!--<label class="column is-2"></label> {{ list.description }}-->
        <!--</li>-->
    <!--</div>-->

    <!--<a class="card " v-for="item,key in temp">
                <span class="column is-9">
                    {{ item.title }}
                </span>
                <span class="column is-9">
                    {{ item.title }}
                </span>
                &lt;!&ndash;<span class="panel-icon column is-1">&ndash;&gt;
                  &lt;!&ndash;<i class="has-text-danger fa fa-trash" aria-hidden="true" @click="del(key,item.id)"></i>&ndash;&gt;
                &lt;!&ndash;</span>&ndash;&gt;
                &lt;!&ndash;<span class="panel-icon column is-1">&ndash;&gt;
                  &lt;!&ndash;<i class="has-text-info fa fa-edit" aria-hidden="true" @click="openUpdate(key)"></i>&ndash;&gt;
                &lt;!&ndash;</span><span class="panel-icon column is-1">&ndash;&gt;
                  &lt;!&ndash;<i class="has-text-primary fa fa-eye" aria-hidden="true" @click="openShow(key)"></i>&ndash;&gt;
                &lt;!&ndash;</span>&ndash;&gt;
    </a>
-->
</template>

<script>
    export default{
            name:"Show",
            props:['openmodal'],
            data(){
                return{
                    list:''
                }
            },
        methods: {
            close()
            {
                this.$emit('closerequest')
            }
        }
    }

</script>