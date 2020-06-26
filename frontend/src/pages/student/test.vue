<template>
    <div>
        <ul>
            <li>Testing one</li>
            <li>Testing two</li>
            <li>Testing 3</li>
            <li>Testing 4</li>
        </ul>

    </div>

</template>

<script>
    import {Sortable, Droppable, Sensors} from "@shopify/draggable";

    export default {
        data() {
            return {};
        },
        mounted() {


            const sortable = new Sortable(document.querySelectorAll('ul'), {
                draggable: 'li'
            });

            // sortable.on('sortable:start', () => console.log('sortable:start'));
            // sortable.on('sortable:sort', () => console.log('sortable:sort'));
            // sortable.on('sortable:sorted', (e) => console.log(e));
            sortable.on('sortable:stop', (e) => console.log(e));


//     const containers = document.querySelectorAll("#UniqueDropzone");
//     const droppable = new Droppable(containers, {
//       draggable: ".Block--isDraggable",
//       dropzone: ".BlockWrapper--isDropzone",
//       sensors: [Sensors.DragSensor],
//       mirror: {
//         constrainDimensions: true,
//         cursorOffsetX: 100,
// cursorOffsetY: 1,
//       }
//     });
//
//     let droppableOrigin;
//
//     // --- Draggable events --- //
//     droppable.on("drag:start", evt => {
//       droppableOrigin = evt.originalSource.parentNode.dataset.dropzone;
//       console.log(evt);
//     });
//
//     droppable.on("droppable:dropped", evt => {
//       console.log(evt);
//       if (droppableOrigin !== evt.dropzone.dataset.dropzone) {
//         evt.cancel();
//       }
//     });
        }
    };
</script>

<style scoped>
    .disabled {
        background-color: gray;
    }

    .inputAnswer {
        width: 80px;
        border-radius: 3px;
        height: 22px;
        border: 1px solid #bbb;
    }

    li {
        float: left;
    }
</style>
