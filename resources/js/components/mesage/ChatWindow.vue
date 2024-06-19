<template>
    <div class="rounded-lg lg:border lg:border-gray-300">
        <div class="flex flex-col h-screen">
            <div
                @click="viewUser()"
                class="flex hover:cursor-pointer items-center ml-4 justify-start py-2 gap-4"
            >
                <img
                    v-if="receiverImage"
                    :src="`/images/${receiverImage}`"
                    :alt="receiverName"
                    class="size-12 rounded-full object-cover"
                />
                <img
                    v-else
                    src="data:image/png;base64,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"
                    class="size-12 rounded-full object-cover"
                />
                <div>
                    <h3 class="text-lg font-medium">
                        {{ receiverName }}
                    </h3>
                </div>
            </div>
            <span class="flex items-center">
                <span class="h-px flex-1 bg-gray-300"></span>
            </span>
            <div class="flex-1 overflow-hidden">
                <div class="message-list" ref="messageList">
                    <ul class="px-2 mt-3">
                        <template
                            v-for="(value, index) in allMessages"
                            :key="index"
                        >
                            <template v-if="isFirstMessageInGroup(index)">
                                <li
                                    :class="{
                                        sent: value.type === 'sent',
                                        received: value.type === 'received',
                                    }"
                                >
                                    <div class="message-content">
                                        <div class="message-image">
                                            <img
                                                v-if="value.image"
                                                :src="`/images/${value.image}`"
                                                alt="Image"
                                            />
                                            <img
                                                v-else
                                                src="data:image/png;base64,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"
                                                class="size-12 rounded-full object-cover"
                                            />
                                        </div>
                                        <div class="message-text">
                                            {{ value.content }}
                                        </div>
                                    </div>
                                </li>
                            </template>
                            <li
                                :class="{
                                    sent: value.type === 'sent',
                                    received: value.type === 'received',
                                }"
                            >
                                <div class="message-content">
                                    <div class="message-text">
                                        {{ value.content }}
                                    </div>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
            <div class="_inputText">
                <div class="flex">
                    <textarea
                        placeholder="Message..."
                        rows="2"
                        lg:row="3"
                        for="messageContent"
                        name="messageContent"
                        v-model="messageContent"
                    ></textarea>
                    <div class="flex justify-end ml-2 items-center">
                        <button @click="sendMessage">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M24 0l-6 22-8.129-7.239 7.802-8.234-10.458 7.227-7.215-1.754 24-12zm-15 16.668v7.332l3.258-4.431-3.258-2.901z"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from "vuex";

export default {
    name: "ChatWindow",
    props: {
        UserId: [Number],
    },
    data() {
        return {
            senderMessages: [],
            receiverMessages: [],
            allMessages: [],
            userInforid: null,
            receiverImage: null,
            receiverName: null,
            messageContent: "",
        };
    },
    created() {
        this.fetchData();
    },
    computed: {
        ...mapGetters(["isLoggedIn", "userData"]),
    },
    methods: {
        viewUser() {
            if (this.isLoggedIn) {
                const id = this.UserId ?? this.$route.params.id;
                this.$router.push({
                    name: "userProfile",
                    params: { id: id },
                });
            }
        },
        isFirstMessageInGroup(index) {
            if (index === 0) {
                return true;
            }
            const currentMessage = this.allMessages[index];
            const previousMessage = this.allMessages[index - 1];
            if (currentMessage.type !== previousMessage.type) {
                return true;
            }
            const currentTime = new Date(currentMessage.datetime);
            const previousTime = new Date(previousMessage.datetime);
            const timeDifference = currentTime - previousTime;
            const timeThreshold = 60000;
            return timeDifference > timeThreshold;
        },

        async fetchData() {
            try {
                const id = this.UserId ?? this.$route.params.id;
                const response = await axios.get(`/api/chat/window/${id}`);
                // console.log(response.data);
                if (response.data.success == true) {
                    const receiverInfor = response.data.receiverInfor;
                    this.userInforid = receiverInfor.id;
                    this.receiverImage = receiverInfor.image;
                    this.receiverName = receiverInfor.name;
                    this.allMessages = [
                        ...response.data.senderMessages.map((message) => ({
                            ...message,
                            type: "sent",
                        })),
                        ...response.data.receiverMessages.map((message) => ({
                            ...message,
                            type: "received",
                        })),
                    ];
                    this.allMessages.sort(
                        (a, b) => new Date(a.datetime) - new Date(b.datetime)
                    );
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                }
            } catch (error) {
                console.error("Error :", error);
            }
        },
        scrollToBottom() {
            const messageList = this.$refs.messageList;
            if (messageList) {
                messageList.scrollTop = messageList.scrollHeight;
            }
        },
        sendMessage() {
            if (this.isLoggedIn && this.userData.id != this.$route.params.id) {
                const id = this.UserId ?? this.$route.params.id;
                axios
                    .post("/api/send-message", {
                        sender_id: this.userData.id,
                        receiver_id: id,
                        content: this.messageContent,
                    })
                    .then((response) => {
                        // console.log(response.data);
                        this.messageContent = "";
                        this.fetchData();
                    })
                    .catch((error) => {
                        console.error("Error sending message:", error);
                    });
            } else {
                this.$router.push({
                    name: "Error",
                });
            }
        },
        formatDateTime(dateTimeString) {
            const postDate = new Date(dateTimeString);
            const now = new Date();
            const diffMilliseconds = now - postDate;
            const diffSeconds = Math.floor(diffMilliseconds / 1000);
            const diffMinutes = Math.floor(diffSeconds / 60);
            const diffHours = Math.floor(diffMinutes / 60);
            const diffDays = Math.floor(diffHours / 24);
            const diffWeeks = Math.floor(diffDays / 7);
            if (diffWeeks >= 1) {
                return `${diffWeeks} last week`;
            } else if (diffDays >= 1) {
                return `${diffDays} yesterday`;
            } else if (diffHours >= 1) {
                return `${diffHours} hours ago`;
            } else if (diffMinutes >= 1) {
                return `${diffMinutes} minute ago`;
            } else {
                return `${diffSeconds} seconds ago`;
            }
        },
    },
};
</script>
<style>
._inputText {
    position: fixed;
    bottom: 0;
    width: 70%;
    background: white;
    padding: 10px;
    box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
}

._inputText textarea {
    width: calc(100%);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    resize: none;
    box-sizing: border-box;
}
.message-list {
    max-height: calc(100vh - 150px);
    overflow-y: auto;
    padding-bottom: 100px;
}
.message-list::-webkit-scrollbar {
    width: 8px;
}

.message-list::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.message-list::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
}

.message-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.message-content {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.message-image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}
.message-image img {
    max-width: 100%;
    height: auto;
    border-radius: 50%;
}

.sender-image {
    float: right;
}

.message-text {
    background-color: #dcf8c6;
    padding: 10px;
    border-radius: 10px;
    max-width: 70%;
}

.received .message-text {
    background-color: #e8e8e8;
}

.sent .message-content {
    justify-content: flex-end;
}

.received .message-content {
    justify-content: flex-start;
}

.sent .message-text {
    background-color: #0099ff;
    color: white;
}

.received .message-text {
    background-color: rgba(232, 232, 232, 0.6);
}

.sent .message-image img {
    transform: scaleX(-1);
}
@media screen and (max-width: 1000px) {
    ._inputText {
        display: block;
        width: 100%;
        bottom: 48px;
        position: fixed;
        z-index: 6666 !important;
    }
}

@media only screen and (max-width: 768px) {
    ._inputText {
        display: block;
        width: 100%;
        bottom: 48px;
        position: fixed;
        z-index: 6666 !important;
    }
}
</style>
